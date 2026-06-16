// Hearth desktop — native backend.
//
// The whole point of the desktop edition: run a *real* model with native GPU (Metal/CUDA),
// not WebGPU-in-a-tab. We do that by talking to Ollama, which runs the model natively.
//
// Why proxy Ollama through Rust instead of fetching it from the webview directly?
// Because the macOS webview (WKWebView) blocks cleartext http://localhost (ATS) and Ollama's
// CORS would reject the tauri:// origin. Rust has neither constraint — so every Ollama call
// goes Rust -> Ollama, and tokens/progress stream back to the UI over a Tauri Channel.

use futures_util::StreamExt;
use serde::{Deserialize, Serialize};
use serde_json::{json, Value};
use std::process::{Command, Stdio};
use std::time::Duration;
use tauri::ipc::Channel;

const OLLAMA_URL: &str = "http://127.0.0.1:11434";

#[derive(Serialize)]
struct OllamaStatus {
    /// Is the `ollama` binary present on this machine?
    installed: bool,
    /// Is the Ollama HTTP server answering on :11434?
    running: bool,
    version: Option<String>,
    /// Models already pulled locally (e.g. "llama3.2:1b").
    models: Vec<String>,
    /// Total system RAM in GB — used to recommend a sensible default brain.
    total_ram_gb: u64,
}

#[derive(Deserialize)]
struct ChatMessage {
    role: String,
    content: String,
}

/// Locate the ollama binary. GUI apps on macOS launch with a minimal PATH that usually
/// omits Homebrew, so we check the common install locations explicitly, then fall back to PATH.
fn ollama_bin() -> Option<String> {
    let candidates = [
        "/opt/homebrew/bin/ollama",
        "/usr/local/bin/ollama",
        "/opt/homebrew/opt/ollama/bin/ollama",
        "ollama",
    ];
    for c in candidates {
        let ok = Command::new(c)
            .arg("--version")
            .stdout(Stdio::null())
            .stderr(Stdio::null())
            .status()
            .map(|s| s.success())
            .unwrap_or(false);
        if ok {
            return Some(c.to_string());
        }
    }
    None
}

fn ollama_version(bin: &str) -> Option<String> {
    let out = Command::new(bin).arg("--version").output().ok()?;
    let s = String::from_utf8_lossy(&out.stdout);
    // Output looks like: "ollama version is 0.30.8"
    s.split_whitespace().last().map(|v| v.trim().to_string())
}

async fn http_running() -> bool {
    let client = reqwest::Client::new();
    client
        .get(format!("{OLLAMA_URL}/api/tags"))
        .timeout(Duration::from_millis(1500))
        .send()
        .await
        .map(|r| r.status().is_success())
        .unwrap_or(false)
}

async fn fetch_models() -> Vec<String> {
    let client = reqwest::Client::new();
    let resp = match client
        .get(format!("{OLLAMA_URL}/api/tags"))
        .timeout(Duration::from_secs(5))
        .send()
        .await
    {
        Ok(r) => r,
        Err(_) => return vec![],
    };
    let body: Value = match resp.json().await {
        Ok(v) => v,
        Err(_) => return vec![],
    };
    body.get("models")
        .and_then(|m| m.as_array())
        .map(|arr| {
            arr.iter()
                .filter_map(|m| m.get("name").and_then(|n| n.as_str()).map(String::from))
                .collect()
        })
        .unwrap_or_default()
}

fn total_ram_gb() -> u64 {
    use sysinfo::System;
    let mut sys = System::new();
    sys.refresh_memory();
    // sysinfo reports bytes; round to nearest GB.
    ((sys.total_memory() as f64) / 1_073_741_824.0).round() as u64
}

#[tauri::command]
async fn ollama_status() -> OllamaStatus {
    let bin = ollama_bin();
    let installed = bin.is_some();
    let version = bin.as_deref().and_then(ollama_version);
    let running = http_running().await;
    let models = if running { fetch_models().await } else { vec![] };
    OllamaStatus {
        installed,
        running,
        version,
        models,
        total_ram_gb: total_ram_gb(),
    }
}

/// Ensure the Ollama server is up. If it's already answering, great. Otherwise spawn
/// `ollama serve` (detached — it keeps running like any local daemon) and wait for it.
#[tauri::command]
async fn start_ollama() -> Result<bool, String> {
    if http_running().await {
        return Ok(true);
    }
    let bin = ollama_bin().ok_or_else(|| "Ollama is not installed".to_string())?;
    Command::new(&bin)
        .arg("serve")
        .env("OLLAMA_HOST", "127.0.0.1:11434")
        .stdout(Stdio::null())
        .stderr(Stdio::null())
        .spawn()
        .map_err(|e| format!("Couldn't start Ollama: {e}"))?;

    // Give it up to ~12s to bind the port.
    for _ in 0..48 {
        if http_running().await {
            return Ok(true);
        }
        tokio::time::sleep(Duration::from_millis(250)).await;
    }
    Err("Ollama was started but didn't come online in time.".to_string())
}

#[tauri::command]
async fn list_models() -> Vec<String> {
    fetch_models().await
}

/// Stream a model download. Emits {type:"progress", status, completed, total} lines and a
/// final {type:"done"} (or {type:"error", message}).
#[tauri::command]
async fn pull_model(model: String, on_event: Channel<Value>) -> Result<(), String> {
    let client = reqwest::Client::new();
    let resp = client
        .post(format!("{OLLAMA_URL}/api/pull"))
        .json(&json!({ "model": model, "stream": true }))
        .send()
        .await
        .map_err(|e| e.to_string())?;

    if !resp.status().is_success() {
        let msg = format!("Ollama returned {}", resp.status());
        let _ = on_event.send(json!({ "type": "error", "message": msg.clone() }));
        return Err(msg);
    }

    let mut stream = resp.bytes_stream();
    let mut buf = String::new();
    while let Some(chunk) = stream.next().await {
        let chunk = chunk.map_err(|e| e.to_string())?;
        buf.push_str(&String::from_utf8_lossy(&chunk));
        // Ollama streams newline-delimited JSON objects.
        while let Some(pos) = buf.find('\n') {
            let line: String = buf.drain(..=pos).collect();
            let line = line.trim();
            if line.is_empty() {
                continue;
            }
            if let Ok(v) = serde_json::from_str::<Value>(line) {
                if let Some(err) = v.get("error").and_then(|e| e.as_str()) {
                    let _ = on_event.send(json!({ "type": "error", "message": err }));
                    return Err(err.to_string());
                }
                let status = v.get("status").and_then(|s| s.as_str()).unwrap_or("");
                let completed = v.get("completed").and_then(|c| c.as_u64()).unwrap_or(0);
                let total = v.get("total").and_then(|t| t.as_u64()).unwrap_or(0);
                let _ = on_event.send(json!({
                    "type": "progress",
                    "status": status,
                    "completed": completed,
                    "total": total
                }));
            }
        }
    }
    let _ = on_event.send(json!({ "type": "done" }));
    Ok(())
}

/// Stream a chat completion. Emits {type:"token", content} as tokens arrive, then {type:"done"}.
#[tauri::command]
async fn chat(
    model: String,
    messages: Vec<ChatMessage>,
    on_event: Channel<Value>,
) -> Result<(), String> {
    let msgs: Vec<Value> = messages
        .iter()
        .map(|m| json!({ "role": m.role, "content": m.content }))
        .collect();

    let client = reqwest::Client::new();
    let resp = client
        .post(format!("{OLLAMA_URL}/api/chat"))
        .json(&json!({ "model": model, "messages": msgs, "stream": true }))
        .send()
        .await
        .map_err(|e| e.to_string())?;

    if !resp.status().is_success() {
        let msg = format!("Ollama returned {}", resp.status());
        let _ = on_event.send(json!({ "type": "error", "message": msg.clone() }));
        return Err(msg);
    }

    let mut stream = resp.bytes_stream();
    let mut buf = String::new();
    while let Some(chunk) = stream.next().await {
        let chunk = chunk.map_err(|e| e.to_string())?;
        buf.push_str(&String::from_utf8_lossy(&chunk));
        while let Some(pos) = buf.find('\n') {
            let line: String = buf.drain(..=pos).collect();
            let line = line.trim();
            if line.is_empty() {
                continue;
            }
            if let Ok(v) = serde_json::from_str::<Value>(line) {
                if let Some(err) = v.get("error").and_then(|e| e.as_str()) {
                    let _ = on_event.send(json!({ "type": "error", "message": err }));
                    return Err(err.to_string());
                }
                if let Some(content) = v
                    .get("message")
                    .and_then(|m| m.get("content"))
                    .and_then(|c| c.as_str())
                {
                    if !content.is_empty() {
                        let _ = on_event.send(json!({ "type": "token", "content": content }));
                    }
                }
                if v.get("done").and_then(|d| d.as_bool()).unwrap_or(false) {
                    let _ = on_event.send(json!({ "type": "done" }));
                }
            }
        }
    }
    Ok(())
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    tauri::Builder::default()
        .plugin(tauri_plugin_opener::init())
        .invoke_handler(tauri::generate_handler![
            ollama_status,
            start_ollama,
            list_models,
            pull_model,
            chat
        ])
        .run(tauri::generate_context!())
        .expect("error while running Hearth");
}
