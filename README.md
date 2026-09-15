# Tiệm Nhà Duy

Nền tảng web **Tiệm Nhà Duy**: site Blade, admin Filament, API Laravel, PWA (`/app`), thanh toán realtime và quản lý license.

Repo GitHub: [weiyuanwen/tiemnhaduy](https://github.com/weiyuanwen/tiemnhaduy)

Laravel nằm **ngay root** (không còn folder `pwa-ecommerce`). Clone xong sẽ thấy `artisan`, `composer.json`, `app/`, `public/`.

## Stack

- Laravel 12, PHP 8.2+ (production CI dùng 8.3)
- MySQL
- Filament 4 (admin `/admin`)
- Laravel Reverb (WebSocket)
- Blade + Vite + Tailwind
- Framework7 PWA tại `public/app/`
- Laravel Sanctum, Scout, WebPush

Backend là nguồn sự thật duy nhất: giá, thời hạn, hết hạn đơn và kích hoạt dịch vụ tính trên server, trong DB transaction. Frontend chỉ hiển thị dữ liệu API.

## Cấu trúc

```
.
├── app/                 # HTTP, Models, Filament, Services, Repositories
├── bootstrap/
├── config/
├── database/            # migrations + seeders
├── public/              # document root
│   ├── index.php
│   └── app/             # PWA Framework7
├── resources/views/     # Blade (site)
├── routes/
│   ├── web.php
│   ├── api.php          # prefix /api/v1
│   └── channels.php
├── tests/
├── artisan
├── composer.json
└── package.json
```

## Yêu cầu

- PHP 8.2+
- Composer 2
- Node.js 20+
- MySQL 8
- Extension: mbstring, xml, bcmath, curl, zip, pdo_mysql

## Cài đặt local

```bash
git clone https://github.com/weiyuanwen/tiemnhaduy.git
cd tiemnhaduy

composer install
npm install
cp .env.example .env
php artisan key:generate
```

Sửa `.env`:

```env
APP_NAME="Tiệm Nhà Duy"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pwa_ecommerce
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_CONNECTION=reverb
```

Nếu terminal đã export `DB_*` (ví dụ Supabase), Laravel **không** đọc `.env` cho các biến đó. Chạy artisan với override tường minh, hoặc `unset` các biến `DB_*` trước.

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
```

Chạy:

```bash
php artisan serve
php artisan reverb:start
php artisan queue:listen
npm run dev
```

Hoặc `composer run dev` (serve + queue + pail + vite).

## Đường dẫn

| | URL |
|---|---|
| Site | `/` |
| Admin Filament | `/admin` |
| PWA | `/app/` |
| API | `/api/v1/` |
| API docs tĩnh | `/api-docs.html` |

Giá gói dịch vụ, thời hạn và trạng thái đơn lấy từ API (`GET /api/v1/services/default`, `POST /api/v1/orders`). Không hardcode trên UI. Chi tiết API: `docs/api.md`, `LICENSE_API_DOCUMENTATION.md`.

Admin quản lý Vendor, Product, Category, Order, Service, License, Review, Collection, Flash sale, User, PC info.

## Realtime

Sau khi tạo đơn, client subscribe **private channel** theo `order_code` (auth trước khi subscribe). Event emit từ server:

- `payment.pending`
- `payment.success`
- `payment.expired`

## Test

```bash
php artisan test
```

## Deploy

Push `main` chạy GitHub Actions (CI + deploy Vietnix). App chạy ở **git root**; document root là `public/`.

Nếu server còn trỏ `.../pwa-ecommerce` hoặc `pwa-ecommerce/public`, đổi `DEPLOY_PATH` về thư mục chứa `artisan` và web root sang `public/` trước khi deploy tiếp.

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run esbuild:build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan optimize
```

Không commit `.env` hay `vendor/`.

## Quy tắc mở rộng gói dịch vụ

Thêm plan = thêm dòng trong bảng `services`. Không đụng core thanh toán, không hardcode plan id / giá trên frontend.
