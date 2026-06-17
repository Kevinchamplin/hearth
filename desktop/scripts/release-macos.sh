#!/usr/bin/env bash
# Build a SIGNED + NOTARIZED, distributable Hearth (Gatekeeper-clean on any Mac).
#
# ── One-time setup ───────────────────────────────────────────────────────────
#   1) Have a "Developer ID Application" cert in your login keychain.
#      Check:  security find-identity -v -p codesigning
#   2) Copy .env.release.example -> .env.release and fill in your identity + Apple ID +
#      app-specific password + team id. (.env.release is gitignored — never commit it.)
#
# ── Run ──────────────────────────────────────────────────────────────────────
#   bash scripts/release-macos.sh
#
# With APPLE_ID + APPLE_PASSWORD + APPLE_TEAM_ID set, Tauri notarizes + staples automatically.
# With only APPLE_SIGNING_IDENTITY set, you get a signed-but-not-notarized build (fine for your
# own Mac; other Macs warn until it's notarized).
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$HERE/.."

# Load creds (gitignored). Exported so `tauri build` sees them.
if [ -f .env.release ]; then
  set -a; . ./.env.release; set +a
fi
: "${APPLE_SIGNING_IDENTITY:?Set APPLE_SIGNING_IDENTITY (in .env.release). Find it: security find-identity -v -p codesigning}"

echo "▶ 0/4  cleanup (stale dmg mounts/temps, running app)"
# A running copy of the app, or a leftover read-write temp dmg from a failed run, will make
# bundle_dmg.sh fail. Clear both.
pkill -f "Hearth.app/Contents/MacOS/hearth-desktop" 2>/dev/null || true
hdiutil info 2>/dev/null | awk '/\/dev\/disk/{d=$1} /[Hh]earth/{print d}' | sort -u | while read -r dev; do
  [ -n "$dev" ] && hdiutil detach "$dev" -force >/dev/null 2>&1 || true
done
rm -f src-tauri/target/release/bundle/macos/rw.*.dmg 2>/dev/null || true

echo "▶ 1/4  bundle the Ollama engine"
bash scripts/bundle-ollama.sh

echo "▶ 2/4  sign the bundled engine (Developer ID + hardened runtime)"
bash scripts/sign-bundled-ollama.sh

echo "▶ 3/4  build + sign the app (and notarize+staple if creds present)"
pnpm tauri build

APP="src-tauri/target/release/bundle/macos/Hearth.app"
DMG="$(ls -t src-tauri/target/release/bundle/dmg/*.dmg 2>/dev/null | head -1 || true)"

echo "▶ 4/4  result"
codesign --verify --deep --strict "$APP" && echo "✓ code signature valid"
if [ -n "${APPLE_ID:-}" ] && [ -n "${APPLE_PASSWORD:-}" ] && [ -n "${APPLE_TEAM_ID:-}" ]; then
  echo "  Notarization was attempted by Tauri. Gatekeeper assessment:"
  spctl -a -t exec -vv "$APP" 2>&1 | head -3 || true
else
  echo "  ⚠ No notarization creds (APPLE_ID/APPLE_PASSWORD/APPLE_TEAM_ID) — built SIGNED but NOT notarized."
  echo "    Other Macs will warn until you add them to .env.release and re-run."
fi
echo "App: $APP"
[ -n "$DMG" ] && echo "DMG: $DMG"
