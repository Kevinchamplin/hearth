#!/usr/bin/env bash
# Populate desktop/src-tauri/resources/ollama/ with the native Ollama engine, so the Hearth
# desktop app SHIPS ITS OWN engine — the user installs one thing (Hearth.app), no separate Ollama.
#
# Source = the locally-installed Ollama (e.g. `brew install ollama`). We copy only the GGUF
# inference path: the `ollama` binary + lib/ollama/{llama-server,llama-quantize}. The MLX runner
# (lib/ollama/mlx_metal_v3) is a symlink to the external `mlx-c` brew dep and is for MLX-format
# models only — Hearth's tiers are all GGUF, and inference is verified without it, so we skip it.
#
# Run this once before `pnpm tauri build`. The copied binaries are gitignored (not redistributed
# via the repo); they only need to exist locally to be folded into the .app bundle.
set -euo pipefail

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DEST="$HERE/../src-tauri/resources/ollama"

OLLAMA_BIN="$(command -v ollama || true)"
if [ -z "$OLLAMA_BIN" ]; then
  echo "✗ ollama not found on PATH. Install it first: brew install ollama" >&2
  exit 1
fi

# Resolve the real binary (Homebrew symlinks bin/ollama -> ../libexec/ollama).
REAL="$(python3 -c 'import os,sys; print(os.path.realpath(sys.argv[1]))' "$OLLAMA_BIN")"
LIBEXEC="$(dirname "$REAL")"
if [ ! -f "$LIBEXEC/lib/ollama/llama-server" ]; then
  echo "✗ Couldn't find Ollama runners at $LIBEXEC/lib/ollama (unexpected install layout)." >&2
  exit 1
fi

rm -rf "$DEST"
mkdir -p "$DEST/lib/ollama"
cp -p "$REAL"                                "$DEST/ollama"
cp -p "$LIBEXEC/lib/ollama/llama-server"     "$DEST/lib/ollama/llama-server"
cp -p "$LIBEXEC/lib/ollama/llama-quantize"   "$DEST/lib/ollama/llama-quantize"
chmod +x "$DEST/ollama" "$DEST/lib/ollama/llama-server" "$DEST/lib/ollama/llama-quantize"

VER="$("$DEST/ollama" --version 2>/dev/null | tail -1 || echo 'unknown')"
echo "✓ Bundled Ollama into resources/ollama  ($VER)"
du -sh "$DEST"
