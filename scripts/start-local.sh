#!/usr/bin/env bash
set -euo pipefail

PORT="${PORT:-4173}"
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

echo "Starting Connecto local preview..."
echo "Open http://localhost:${PORT} in your browser."
echo "Press Ctrl+C to stop the server."

python3 -m http.server "${PORT}" --directory "${ROOT_DIR}/local-preview"
