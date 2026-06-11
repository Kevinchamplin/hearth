# Changelog

All notable changes to Hearth are documented here.

## [Unreleased]

### Added (2026-06-11, hearth-why-free-and-name-meaning) [0.5h]
- Answered the two unspoken visitor questions. **"Why give it away?"** — a 3-card trio under
  the builder's note (for the curious / for the love of it / because it was missing) closing
  with the honest trade: "my name on work I'm proud of." **"Why 'Hearth'?"** — name-meaning
  section on /about (the hearth was the home's own fire — nobody billed you monthly for it;
  the cloud is somebody else's fire, rented) + the free-economics explainer (runs on your
  computer → no server bill → free is real, not a trick). Two new/upgraded FAQ entries.

### Added (2026-06-11, hearth-dream-team-marketing-pass) [1h]
- On-site marketing pass from the /dream-team panel (Godin/Hormozi/Kennedy). Four moves:
  **(1) Feature stack** — new "The whole feature list. All of it free." section: 8 plain-named
  cards (just talk, show it photos, three brains, remembers your chats, no internet needed,
  lives in your Dock, copy & print, no account ever) + kicker "no locked buttons, no Pro tier."
  **(2) Builder's note** — signed letter with Kevin's photo ("The big AI companies rent you a
  seat in their data center… I wanted to hand you the keys instead. It's signed work.") linking
  kevinchamplin.com + GitHub — builder credibility without naming engines.
  **(3) Wait priced in minutes** — "a few minutes from now, you own an AI" (GB → minutes).
  **(4) Tell-a-friend remark** — hands visitors the script: "It's a free AI that runs on your
  own computer — it even works with the wifi off."

### Changed (2026-06-11, hearth-hero-demo-window) [0.75h]
- Redesigned the hero from a centered stack into a **two-column above-the-fold story**: message
  left (eyebrow, plain-spoken H1, lead, CTAs, one-row $0/100%/1 stat trio), and a **glowing mock
  Hearth window** right — a CSS-built live demo where the conversation sells the product itself
  (dinner question → "did anyone else just see that?" → "Nobody. I run right here on your
  computer"), topped with a "✈ Wifi off — still working" badge. Staggered load animation,
  breathing ember seeds, reduced-motion respected. At 1440×900 the entire what/why/proof story
  now fits above the fold; mobile stacks with the CTA still above the fold.

### Added (2026-06-11, hearth-pexels-photo-strip) [0.5h]
- Added curated lifestyle photography (Pexels free license, self-hosted in
  `site/assets/photos/`): a 3-up **photo strip** under the comparison table — plane-window
  sunset ("no wifi, no problem"), snowy cabin ("off the grid"), warm kitchen ("what can I make
  with what's in the fridge?") — plus a fireside image on /about. All warm-graded via CSS
  (sepia/saturate filter) to sit inside the Hearth palette; lazy-loaded with width/height attrs.
- Gotcha: an explicit `height` attribute on `<img>` defeats CSS `aspect-ratio` (it only applies
  when height is auto) — fixed with `height:auto` in the CSS.

### Changed (2026-06-11, hearth-works-without-internet-wording) [0.1h]
- Swapped the jargon-y "works offline" for plain "**works without internet**" everywhere it
  carries weight — meta/OG descriptions, hero stat trio, props card, comparison-table row, app
  gate fineprint — and upgraded the FAQ entry ("Does it really work without internet?") with the
  try-it-yourself wifi-off challenge.

### Changed (2026-06-11, hearth-landing-conversion-and-install-guide) [1.5h]
- **Conversion + comprehension pass on the landing.** Hero now says it plainly — H1 "A free,
  private AI that lives on your own computer." (poetry moved to the eyebrow), concrete ask-it-
  anything lead, and a **$0 / 100% / 1-download stat trio**. New **comparison table** answering
  "how is this different from ChatGPT?" (price/privacy/offline/account — with an honest "the
  cloud wins at huge projects" row). The download reframed as **"The only catch — and we'll name
  it ourselves"** (~1 GB once, movie-before-a-flight analogy, "that's the entire price").
- **SEO:** title/OG/Twitter now keyword-rich — "Hearth — Free, Private AI That Runs on Your
  Computer" (H1 was previously pure poetry with zero search terms).
- **Interactive install guide** on the landing: auto-detects browser/OS ("It looks like you're
  using Chrome on a Mac"), tabbed plain-language steps for Chrome / Edge / Safari / phones with
  honest caveats. **In-app:** the ⬇ Install button now always shows (hidden when already
  installed); without a native prompt it opens a per-browser step overlay.

### Added (2026-06-11, hearth-pwa-and-memory) [2h]
- **True offline (PWA):** service worker caches the app shell + JS libraries (model weights left
  to WebLLM's own cache; analytics never cached), web manifest + ember icons (192/512/maskable),
  theme-color/apple-touch meta, and an **Install** button (beforeinstallprompt) so Hearth lives in
  the dock/home screen as a real app. **Verified with Playwright: page fully loads with the
  network killed** — the "works offline" promise is now literally true (previously only the model
  weights were cached; the page itself needed network).
- **Conversation memory:** chats now auto-save locally (IndexedDB — this device only, never
  uploaded). New 💬 drawer: past conversations with title/date/exchange count, tap to resume
  (model context rebuilt), ＋ New chat, hover-× delete. Vision exchanges saved as text (📷
  prefix). FAQ entry added for installing.

### Added (2026-06-09, hearth-seo-opengraph) [1h]
- **SEO + OpenGraph pass.** Added `robots.txt` (welcomes AI bots — GPTBot/ClaudeBot/PerplexityBot/
  OAI-SearchBot/Google-Extended — and points to the sitemap), `sitemap.xml` (all 5 pages),
  `llms.txt` (high AI-citation leverage for an AI product), an ember `favicon.svg`, and
  self-referential `canonical` tags on every page (none existed before).
- **OpenGraph:** generated a branded **1200×630 OG card** (`assets/og.png`) and wired it to
  `og:image` + Twitter `summary_large_image` site-wide — the landing was sharing with **no image at all**.
- **Structured data** (was 0 blocks anywhere): Organization + WebSite + SoftwareApplication
  (Offer price 0 → "Free" rich-result eligible) on the landing; **FAQPage** (12 Q&As, data-driven
  from the `$faqs` array) on /faq — all validated as parseable JSON-LD.
- Made Google Fonts non-render-blocking (Perf 78→83). **LLM-discoverability: D → A.**

### Added (2026-06-09, hearth-brain-discoverability) [0.2h]
- Made the brain switcher discoverable: added a ▾ caret to the model pill (so it reads as a menu)
  and a friendly **one-time nudge** ("Want smarter answers? Tap the brain button up here to
  switch") that points at it on first chat load — dismissible, shown only once (localStorage).

### Added (2026-06-09, hearth-picker-computer-fit) [0.25h]
- The brain picker now shows the visitor's own computer (memory + processor cores, and GPU where
  the browser exposes it) and gives each brain a plain verdict badge — "✓ Good for your computer"
  / "⚠ Might be slow here" / "✗ Likely too much" — so non-technical users can see what their
  machine can handle before downloading. Uses navigator.deviceMemory / hardwareConcurrency /
  WebGPU adapter info.

### Fixed (2026-06-09, hearth-honest-browser-copy) [0.1h]
- Corrected a false claim in the landing's browser callout ("Try it instantly… no download") —
  there IS a one-time ~1 GB model download. Now reads "No app to install… downloads the AI once
  (about a minute), then it's instant and works offline." On a brand built on honesty, this matters.

### Added (2026-06-09, hearth-brain-picker) [0.75h]
- Added an in-app **brain picker**: tap the model pill to open "Choose Hearth's brain" — three
  plain-language options (Fast ~1 GB · Smart ~2 GB · Genius ~5 GB) with friendly descriptions,
  download sizes, a "might be slow on this computer" hint on low-RAM machines, and one-tap
  switching. The choice is remembered. Lets non-technical users download a smarter model with
  zero jargon. (Models: Llama-3.2-1B / Llama-3.2-3B / Llama-3.1-8B via WebLLM.)

### Changed (2026-06-09, hearth-vision-500m) [0.25h]
- Vision looped garbage ("1.1.1.1…") on the 256M model (too small + greedy decoding). Switched to
  **SmolVLM-500M-Instruct** (the size that actually describes images in-browser) and added
  anti-repetition generation (`repetition_penalty: 1.2`, `no_repeat_ngram_size: 3`). Heftier
  one-time download, but real answers.

### Fixed (2026-06-09, hearth-vision-progress-and-blob) [0.5h]
- Vision "failed to fetch": the image's blob URL was being revoked (in clearPending) before the
  still-downloading vision model actually read it. Now the image is kept alive until the model is
  done with it, then released ~1.5s later.
- Vision loading no longer shows a per-file percentage that resets and appears to "spin forever" —
  it now shows a steadily-climbing "NN MB downloaded (one-time only)" counter.

### Fixed (2026-06-09, hearth-vision-version-fix) [0.25h]
- Vision was failing with "Unsupported model type: idefics3" — transformers.js was pinned too
  old (3.0.2), before SmolVLM/Idefics3 support. Bumped to 3.8.1. Confirmed the model's ONNX
  weights (q4 decoder + fp16 embed/vision encoder) exist, so the dtype config was already right.

### Added (2026-06-09, hearth-expectations-and-chamber-culture) [0.25h]
- Set expectations in plain language: a "One honest note" callout on the landing + a rewritten
  FAQ answer — Hearth isn't a ChatGPT/Gemini competitor (no data center; it's your computer),
  which is why it's simple, fast, private. Great for recipes, explanations, writing, brainstorming;
  not for building a full website.
- Added **Chamber Culture** (chamberculture.com) to the footer project links.

### Added (2026-06-09, hearth-explainer-plain-language) [0.25h]
- Explained the "runs on your device · ~1 GB · best in Chrome/Edge" promise in plain,
  non-technical language: a friendly "A few honest details" section on the landing (with
  movie-download and graphics-chip analogies), two new FAQ entries, and a gentle "Wait — why?"
  link on the app's start screen pointing to the FAQ.

### Added (2026-06-09, hearth-site-pages-nav-contact) [1.5h]
- Added a shared, mom/dad-readable **header** (About · How it works · FAQ · Contact · Try Hearth)
  and a dark **footer** with project links (Kevin Champlin, The Mirror, BrandForge, BridgeCare OS)
  and a "Built by Kevin Champlin" attribution — across the landing and the new pages (PHP includes).
- New pages: **/about**, **/faq** (10 plain-English Q&As), **/contact** (form with honeypot spam guard).
- Contact form sends via the shared CE **Mailgun** (mg.champlinenterprises.com); the API key lives
  server-only outside the docroot (`hearth-analytics-data/mailgun.php`), never in the repo. Verified
  end-to-end delivery to kevin@kevinchamplin.com.

### Added (2026-06-09, hearth-view-source-easter-eggs) [0.25h]
- Added a witty view-source welcome comment, a devtools console greeting, and a couple of
  cheeky inline comments for the snoopers — all on-brand (privacy-proud, warm). Cosmetic only.

### Added (2026-06-09, hearth-markdown-copy-print) [0.5h]
- Hearth's replies now render **markdown as HTML** (bold, lists, headings, code blocks, tables,
  links), sanitized with DOMPurify (no XSS).
- Added per-message **Copy** buttons and a top-bar **Print** button (with print styles for a
  clean black-on-white printout of the conversation).
- Fixed a stray empty container above the input — the image-preview chip was rendering even when
  `hidden`, because the author rule `.imgchip{display:flex}` overrode the `hidden` attribute;
  added `.imgchip[hidden]{display:none}`.

### Added (2026-06-09, hearth-vision-smolvlm) [1h]
- Added on-device **vision** to the browser app: attach an image and ask about it. Lazy-loads
  SmolVLM-256M via transformers.js on WebGPU only when first used — still 100% private, nothing
  leaves the device. Attach (📎) button + image thumbnails in the conversation; the text chat is
  untouched and the feature fails gracefully (error shown in-bubble, app keeps working).

### Added (2026-06-09, hearth-launch-post) [0.5h]
- Published the build-in-public launch post on kevinchamplin.com ("Your AI shouldn't live in
  someone else's computer") with a featured ember image, two inline screenshots, and a CTA to
  hearth.kevinchamplin.com. Draft saved in repo at `blog/`.

### Added (2026-06-09, hearth-github-public) [0.25h]
- Published the repo **public** at https://github.com/Kevinchamplin/hearth (MIT licensed) and
  wired the site's "Star on GitHub" + footer links to it (were dead `#` placeholders).

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
