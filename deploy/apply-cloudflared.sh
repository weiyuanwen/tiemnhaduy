#!/usr/bin/env bash
set -euo pipefail
SRC=/home/edward/.cloudflared/config.yml
DST=/etc/cloudflared/config.yml
if [[ $EUID -ne 0 ]]; then
  echo "Chay: sudo $0"
  exit 1
fi
install -m 644 -o root -g root "$SRC" "$DST"
systemctl restart cloudflared
systemctl --no-pager --full status cloudflared | head -20
echo "OK: tunnel da nhan tiemnhaduy.com -> 127.0.0.1:9090"
