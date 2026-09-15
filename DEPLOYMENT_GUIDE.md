# CI/CD lên VPS

Push `main` → GitHub Actions build → **rsync** lên VPS. Domain đã trỏ VPS; không `git pull` trên server.

```mermaid
flowchart LR
    A[git push main] --> B[GitHub Actions]
    B -->|Composer + Vite| C[Build]
    C -->|rsync| D[VPS]
    D -->|migrate + optimize| E["tiemnhaduy.com"]
```

## Secrets (GitHub → Settings → Secrets → Actions)

| Secret | Bắt buộc | Ý nghĩa |
|--------|----------|---------|
| `DEPLOY_HOST` | có | IP VPS hoặc hostname SSH |
| `DEPLOY_USER` | có | User SSH (ví dụ `deploy`, `ubuntu`, `root`) |
| `DEPLOY_PATH` | có | Thư mục có file `artisan`, ví dụ `/var/www/tiemnhaduy` |
| `SSH_PRIVATE_KEY` | có | Private key GitHub Actions dùng SSH vào VPS |
| `DEPLOY_PORT` | không | Mặc định `22` |
| `DEPLOY_URL` | không | `https://tiemnhaduy.com` — health check sau deploy |
| `PHP_BIN` | không | Nếu PHP không phải `php`, ví dụ `/usr/bin/php8.3` |
| `DEPLOY_POST_CMD` | không | Ví dụ reload PHP-FPM/Nginx: `sudo systemctl reload php8.3-fpm && sudo systemctl reload nginx` |
| `TELEGRAM_BOT_TOKEN` / `TELEGRAM_CHAT_ID` | không | Thông báo |

`DEPLOY_PATH` **không** được trỏ vào `public/` — Nginx/Caddy document root mới là `DEPLOY_PATH/public`.

`.env` trên VPS tạo **một lần**, CI không ghi đè. Upload trong `storage/` được giữ.

## VPS cần có

- SSH key của GitHub Actions trong `~/.ssh/authorized_keys`
- `rsync`, PHP 8.2+, Composer không bắt buộc (vendor được build trên Actions)
- Web server trỏ `root` / `document root` = `.../tiemnhaduy/public`
- Quyền ghi `storage/` và `bootstrap/cache`

## Chạy

- Mỗi push `main` deploy production
- PR chỉ chạy test (`.github/workflows/ci.yml`)
- Deploy tay: Actions → **Deploy production** → Run workflow

Khẩn cấp trên VPS (không khuyến nghị): `./scripts/deploy.sh --from-git`
