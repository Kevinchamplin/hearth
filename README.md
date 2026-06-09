<div align="center">

# 🔥 Hearth

### A warm light in a cold cloud.

A beautiful, **free**, fully-local AI that runs entirely on your own computer.
No account. No internet required. Nobody watching. Your mom could use it.

**Live → [hearth.kevinchamplin.com](https://hearth.kevinchamplin.com)**

</div>

---

## What is this?

Every AI you've used lives in someone else's data center — metered, logged, and one
outage away from gone. Hearth is the opposite: a calm, gorgeous AI that runs **on your
machine**, works **offline**, costs **nothing**, and keeps every word **private**.

It's built for people who've never heard the word "LLM." You open it, and you're
talking to it in seconds.

## Why local?

- 🔒 **Private by nature** — your conversations never leave your computer.
- ♾️ **Unlimited & free** — no caps, no counters, no subscription. It runs on *your*
  hardware, so there's nothing to bill.
- ✈️ **Works offline** — on a plane, in a cabin, anywhere. No internet needed once set up.
- 🏡 **Yours** — no account, no login, no tracking. It lives with you.

## Runs anywhere — no install required

- 🌐 **In your browser** — open Hearth in Chrome and start talking. Nothing to install;
  the model runs right in your tab via WebGPU, fully private. The fastest way in.
- 🖥️ **As a native app** — macOS, Windows, and Linux from one codebase (Tauri). Unlocks
  the bigger, smarter models.

*Same interface, two engines: WebLLM in the browser, a bundled Ollama in the app.*

## A look

| Home (night) | Conversation (night) |
|---|---|
| ![Home](mockup/screenshots/hearth-home-dark.png) | ![Chat](mockup/screenshots/hearth-chat-dark.png) |

*Design mockup — open `mockup/index.html` in a browser to watch the ember breathe.*

## Design language

Hearth rejects the blue-glow, circuit-board, robot aesthetic of every other AI tool.
The guiding idea: **intelligence as warm light, not cold tech.** A breathing ember is
the AI's presence — warm paper by day, a glowing hearth in a dark room by night.

- **Type:** Fraunces (display) · Inter (body) · JetBrains Mono (labels)
- **Color:** Warm Paper `#FBFAF7` · Ink `#2B2720` · Brass `#C8A15A` · Ember `#FFCC84 → #EE9A45`
- **Feel:** a calm companion, not a cockpit.

## How it works (planned)

One interface, two runtimes:

- **In the browser** — the UI runs as a web app with [WebLLM](https://github.com/mlc-ai/web-llm) / WebGPU
  as the engine. The model runs inside the tab; nothing is installed and nothing leaves your
  machine. Chrome/Edge first, others as WebGPU lands.
- **As a desktop app** — the *same* UI in a [Tauri](https://tauri.app) shell with a bundled
  [Ollama](https://ollama.com) engine, so you install **one** thing. macOS, Windows, Linux.

- **Models:** open-weight models (Qwen, Llama, Mistral) as quantized GGUF.
- **Instant first run:** a tiny model is ready immediately so your first reply lands in
  seconds, while a smarter model loads in the background. Hearth reads your hardware and
  picks the right brain — **Fast · Smart · Genius** — automatically.
- **Reach:** the browser is the zero-install **Fast** tier; the app unlocks **Smart** and **Genius**.

## Status

🌱 Early — design complete and the **showcase site is [live](https://hearth.kevinchamplin.com)**.
The browser app and desktop builds are in active, in-the-open development.

---

*Made by [Kevin Champlin](https://kevinchamplin.com).*
