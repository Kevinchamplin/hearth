#!/usr/bin/env bash
# Sign the bundled Ollama binaries with a Developer ID + hardened runtime, so the notarized
# Hearth.app passes Gatekeeper on any Mac. Tauri signs the app *shell*, but the loose Mach-O
# binaries we ship in Resources (the engine + its runners) must be signed independently — this
# does that. Run AFTER bundle-ollama.sh and BEFORE `tauri build` (Tauri copies these
# already-signed binaries into the .app, then signs the shell around them).
#
# Verified: Ollama's GGUF inference runs fine under hardened runtime with NO extra entitlements,
# so we sign with plain `--options runtime` (no entitlements file needed).
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
R="$HERE/../src-tauri/resources/ollama"
ID="${APPLE_SIGNING_IDENTITY:-${1:-}}"

if [ -z "$ID" ]; then
  echo "✗ Set APPLE_SIGNING_IDENTITY (or pass the identity as arg 1)." >&2
  echo "  Find it with:  security find-identity -v -p codesigning" >&2
  echo "  e.g. 'Developer ID Application: Your Name (TEAMID)'" >&2
  exit 1
fi
if [ ! -f "$R/ollama" ]; then
  echo "✗ $R/ollama not found — run scripts/bundle-ollama.sh first." >&2
  exit 1
fi

# Inner binaries first, then the parent (codesign convention: sign nested code before the container).
for b in "$R/lib/ollama/llama-server" "$R/lib/ollama/llama-quantize" "$R/ollama"; do
  codesign --force --options runtime --timestamp --sign "$ID" "$b"
  codesign --verify --strict "$b"
  echo "✓ signed $(basename "$b")"
done
echo "✓ Bundled engine signed with: $ID"
