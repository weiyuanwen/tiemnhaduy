#!/usr/bin/env bash
# Chạy trên server sau khi GitHub Actions rsync xong.
# Không git pull — code đã được đẩy từ CI.
set -euo pipefail

ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

if [ ! -f artisan ]; then
  echo "DEPLOY_PATH phải là root Laravel trên VPS (có file artisan), không phải thư mục public/."
  exit 1
fi

if [ ! -f .env ]; then
  echo "Thiếu .env trên server. Tạo một lần rồi giữ nguyên — CI không ghi đè file này."
  exit 1
fi

if [ -f deploy/docker-compose.yml ]; then
  echo "Docker release..."
  docker compose -f deploy/docker-compose.yml --env-file .env up -d --build
  echo "Release OK (docker compose)"
  exit 0
fi

PHP_BIN="${PHP_BIN:-php}"
SKIP_MIGRATION="${SKIP_MIGRATION:-false}"

if ! command -v "$PHP_BIN" >/dev/null 2>&1; then
  echo "Không có $PHP_BIN trên host và không thấy deploy/docker-compose.yml."
  exit 1
fi

mkdir -p \
  storage/framework/cache/data \
  storage/framework/sessions \
  storage/framework/views \
  storage/logs \
  bootstrap/cache

if [ "$SKIP_MIGRATION" != "true" ]; then
  echo "Running migrations..."
  "$PHP_BIN" artisan migrate --force --no-interaction
fi

echo "Building caches..."
"$PHP_BIN" artisan storage:link --force >/dev/null 2>&1 || true
"$PHP_BIN" artisan optimize
"$PHP_BIN" artisan queue:restart >/dev/null 2>&1 || true

chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true
chmod 644 .env 2>/dev/null || true

echo "Release OK: $("$PHP_BIN" artisan --version) @ $(git rev-parse --short HEAD 2>/dev/null || echo nosha)"
