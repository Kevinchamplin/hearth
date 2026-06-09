# Changelog

All notable changes to Hearth are documented here.

## [Unreleased]

### Added (2026-06-09, hearth-analytics-god-admin) [2h]
- Built a first-party, privacy-respecting analytics system + **god-admin dashboard** at
  `/analytics` (password-gated, `noindex`). Anonymous, **content-free** telemetry — never
  touches conversation text. Captures pageviews, CTA clicks, the activation funnel
  (app opened → Hearth lit → model ready → message sent), OS / browser / device, screen,
  locale, timezone (privacy-friendly geo proxy), referrer, WebGPU support, model load times,
  and unique visitors via a one-way salted IP hash (raw IP never stored). PHP 8.4 + SQLite;
  DB + secrets live OUTSIDE the web docroot in `hearth-analytics-data/`.
- Dashboard: KPI cards, activity-over-time chart (Chart.js), conversion funnel,
  OS/browser/device + referrer/timezone/language breakdowns, live recent-events feed, date
  filters (24h/7d/30d/90d/all), bot toggle, and CSV export.
- Beacon (`analytics/a.js`) on the landing + app; app instrumented for `hearth_lit`,
  `model_ready` (with load time), `message_sent` (count only — never content), `webgpu_missing`.

### Fixed (2026-06-09, hearth-chat-empty-layout) [0.25h]
- Fixed the empty-state layout where the input box was pinned to the very bottom with a dead
  gap above it. Now the greeting + suggestions + input center as one group (ChatGPT-style
  new-chat screen); once a conversation starts, the input docks to the bottom and messages
  fill upward. Also removed a double height-calc that could push the composer off-screen.

### Added (2026-06-09, pulse-pixel-wired) [0.25h]
- Wired the site into **Pulse** analytics (pulse.champlinenterprises.com) — pixel on both the
  landing page and the `/app` page (Pulse Site id 185). Pageviews/events now tracked. Verified
  the snippet renders in the served HTML and the pixel beacon records (200, real UA). No CSP to
  adjust.

### Changed (2026-06-09, hearth-chat-ux-polish) [0.75h]
- UX-designer pass on the in-browser chat: replaced the dated native scrollbar with a thin,
  warm custom one + a soft top fade, added gutters and a ~70-char reading column, refined
  message typography/spacing, dropped the redundant "YOU" label for a subtle user pill, and
  added `prefers-reduced-motion` support.
- Warmer first-load copy (fireside phrases — "Gathering the kindling…", "Warming the room…",
  "NN% home" — instead of WebLLM's technical text) and a **local-time greeting** (good
  morning/afternoon/evening from the visitor's own device clock).
- Tightened Hearth's system prompt so it keeps replies short and stops rambling/contradicting.

### Added (2026-06-09, hearth-web-app-deployed) [0.5h]
- Deployed the in-browser app **live** to https://hearth.kevinchamplin.com/app (rsynced
  `web/` to the subdomain's `app/` dir) and wired the landing page's "Try it in your browser"
  / "Launch in Chrome" CTAs to it — flipping the browser option from "Coming soon" to live.
  Anyone can now talk to Hearth on their own device, one click from the homepage. The
  Mac/Windows/Linux apps remain coming soon.

### Added (2026-06-09, hearth-web-app-webllm) [1.5h]
- Built the **working browser app** (`web/index.html`): the real Hearth running in-tab via
  WebLLM + WebGPU (Llama-3.2-1B), with **no server inference** — the model runs on the
  visitor's own device. Includes a "Light the hearth" gate, a one-time model download with
  progress + reassurance, streaming replies, the breathing ember, and a calm WebGPU-missing
  fallback. Served locally for testing via `python3 -m http.server` on :8782.
- Confirmed the host (ce-prod: 8-core EPYC, 15 GB RAM, **no GPU**) can't and needn't run
  inference — it only serves files; the local-first design is what makes Hearth free + private.

### Added (2026-06-09, hearth-site-deployed) [0.5h]
- Deployed the showcase site **live** to https://hearth.kevinchamplin.com — a Plesk
  subdomain on kc-prod (docroot `hearth.kevinchamplin.com`, system user
  `kevinchamplin.com_gu0sct6n6k`), DNS A record in the Plesk-managed zone, Let's Encrypt
  certificate (auto-renew), HTTP→HTTPS 301 redirect. Verified HTTP/2 200 + valid cert.

### Added (2026-06-09, hearth-landing-site-and-web-runtime) [1.25h]
- Built the **showcase landing site** (`site/index.html`) — dark fireside hero with the
  breathing ember, value props, app-screenshot showcase, 3-step how-it-works, a manifesto
  band, and a download section. Targets `hearth.kevinchamplin.com` (not yet deployed).
- Decided a **dual runtime** for maximum reach and zero-barrier entry: the *same* UI ships
  as (1) a browser app (WebLLM / WebGPU — runs in Chrome, no install) and (2) a Tauri
  desktop app (Ollama engine — macOS / Windows / Linux). Browser = the "Fast" no-install
  tier; the app unlocks Smart / Genius.
- Reworked the site to lead with **"Try it in your browser"** as the primary call to action.

### Added (2026-06-09, hearth-concept-and-mockup) [1.5h]
- Named the project **Hearth** and locked the positioning — "A warm light in a cold
  cloud": a calm, private, beautiful local AI for people who've never heard the word "LLM."
- Chose the architecture: **Tauri shell + custom UI + bundled Ollama sidecar** (wrap the
  engine, own the interface). Open-weight models (Qwen / Llama / Mistral) shipped as
  quantized GGUF; a tiny model bundled inside the app for an instant, zero-wait first run.
- Designed the **first-run flow** (instant tiny-model wow → background upgrade → hardware
  auto-tiering as "Fast / Smart / Genius," no jargon, no account) and the **main chat room**
  (breathing-ember presence, letter-not-bubbles, warm-light-not-cold-tech, no usage counters).
- Built a self-contained HTML **mockup** (`mockup/index.html`) in light + night modes and
  captured reference screenshots.
- Confirmed **`hearth.computer`** is available (exact-match domain); repo to live under a
  personal GitHub namespace for portfolio visibility.
