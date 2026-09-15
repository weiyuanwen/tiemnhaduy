# Spec: Thanh toán VietQR + histbank + Reverb (Tiệm Nhà Duy)

Ngày: 2026-09-15  
Repo: `weiyuanwen/tiemnhaduy` trên VPS `~/tiemnhaduy`  
Domain: `https://tiemnhaduy.com`

## Mục tiêu

User dán link Facebook → Laravel tạo đơn → hiện QR VietQR (đúng STK, đúng số tiền, nội dung CK = mã đơn) → một poller gọi histbank khi có đơn pending → khớp nội dung CK + số tiền → Reverb bắn `payment.success` + gửi email nếu có.

## Ngoài phạm vi

- Tự duyệt thành viên / bài viết Facebook.
- Watcher histbank 10 giây chạy 24/7.
- Mỗi user một vòng poll bank riêng.
- Thanh toán giỏ hàng e-commerce (`orders` table) — chỉ `service_orders`.

## Hiện trạng source (đã có)

- `POST /api/v1/orders` tạo `ServiceOrder`, mã `ORDFB` + 10 ký tự, event `PaymentPending`.
- `PaymentSuccess` / `PaymentExpired` đã `ShouldBroadcastNow` trên `private-order.{order_code}`.
- `verifyPayment($orderCode, $bankTxnId)` đánh dấu paid + web push.
- QR đang giả: `bank:{order_code}:{amount}`.
- Cửa sổ hết hạn **5 phút**.
- `laravel/reverb` + `laravel-echo` đã trong composer/package.json; Docker chưa có service Reverb; `BROADCAST_CONNECTION` production chưa chắc là `reverb`.
- histbank nằm `~/histbank`, **không đang chạy**.

## Quyết định đã chốt

Hướng 1: **Laravel kéo sao kê**. Histbank chỉ là HTTP API nội bộ. Không dùng watcher webhook làm nguồn chính.

## Luồng

```
[SPA] dán FB URL + email?
    → POST /api/v1/orders
    → ServiceOrder pending, expires_at = now+12 phút
    → qr_image_url VietQR, addInfo = order_code
[SPA] Echo.subscribe private-order.{code}
    fallback GET /api/v1/orders/{code} mỗi 3s
[Scheduler ~15s] bank:poll-pending
    → 0 pending? return (0 gọi bank)
    → đơn mới < 20s? skip fetch
    → adaptive delay chưa tới? skip fetch
    → GET histbank /transactions?days=1&pageSize=100
    → matcher: CRDT + description chứa \bORDFB[A-Z0-9]{10}\b + amount == order.amount
    → OrderService::confirmBankMatch (lock, dedupe bank_txn_id)
    → PaymentSuccess → Reverb + Mail (nếu email)
Hết hạn: mark expired + PaymentExpired
02:30: bank:reconcile-overnight (pending/expired 24h, bắt CK muộn)
```

## Thành phần

| Unit | Trách nhiệm | Không làm |
|---|---|---|
| `HistbankClient` | HTTP GET `/health`, `/transactions` | Khớp mã, đánh dấu paid |
| `BankTransferMatcher` | Từ list tx + list pending orders → match | Gọi bank |
| `PendingBankPoller` | Idle skip, delay, backoff, gọi client+matcher | Broadcast |
| `OrderService::confirmBankMatch` | Lock row, paid, fire events | Gọi bank |
| `VietQrService` | URL ảnh QR | Poll bank |
| Reverb container | WS `0.0.0.0:8080` → host `127.0.0.1:9091` | Origin HTTP |
| histbank `tpbank-serve` | TPBank login+history bind nội bộ | Biết đơn Laravel |

## Khớp CK (bắt buộc)

- Chỉ `creditDebitIndicator === CRDT`.
- `description` normalize NFC, collapse whitespace.
- Regex word-boundary: `\bORDFB[A-Z0-9]{10}\b` (case-insensitive).
- `amount` số nguyên VND **đúng bằng** `service_orders.amount` (không dùng minAmount lỏng).
- Dedupe `tx.id` → `bank_txn_id` unique.
- Không khớp theo tên người gửi / STK chung.

## Gọi bank (chống khóa TPBank)

- 0 đơn pending → 0 request histbank.
- N đơn pending → 1 request / tick.
- 0–20s sau đơn mới nhất: chưa fetch.
- 20s–2 phút: tối thiểu 25s giữa 2 fetch.
- 2–6 phút: 40s.
- 6–12 phút: 60s.
- HTTP 401/423/429: backoff 2 phút → 5 phút → 15 phút; log; không retry 15s.
- Query: `days=1`, `pageSize=100`. Không kéo 30 ngày.
- Histbank tự cache token ~15 phút (client hiện có). Laravel không login TPBank.

## Cửa sổ thanh toán

- `expires_at = now()->addMinutes(12)` (config `payment.window_minutes=12`).
- Hết hạn: UI hết giờ; nightly reconcile vẫn quét CK có mã đơn trong 24h để khỏi mất tiền im lặng. Nếu khớp sau expire: đánh dấu paid + ghi `meta`/log `paid_after_expiry=true` (không từ chối tiền đã vào).

## QR

VietQR image:

`https://img.vietqr.io/image/{bank}-{account}-compact2.png?amount={amount}&addInfo={order_code}&accountName={urlencoded}`

`addInfo` = đúng `order_code`. User không được tự gõ nội dung.

Config: `VIETQR_BANK_ID` (TPB), `VIETQR_ACCOUNT_NO`, `VIETQR_ACCOUNT_NAME`.

Response tạo đơn thêm: `qr_image_url`, `transfer_content` (= order_code), `bank_account`, `amount`. Giữ `qr_content` cũ nếu SPA còn đọc, nhưng giá trị mới = `transfer_content`.

## Email

- Field mới `service_orders.customer_email` nullable.
- `POST /api/v1/orders` nhận `email` optional, validate `email`.
- Paid → `ServiceOrderPaidMail` nếu email có. Không chặn thanh toán khi trống.
- Test: `MAIL_MAILER=array`.

## Reverb

- Container giống hotel-hub: `php artisan reverb:start --host=0.0.0.0 --port=8080`, publish `127.0.0.1:9091:8080`.
- Env: `BROADCAST_CONNECTION=reverb`, `REVERB_HOST=ws.tiemnhaduy.com`, `REVERB_PORT=443`, `REVERB_SCHEME=https`.
- Cloudflare tunnel `homelab`: hostname `ws.tiemnhaduy.com` → `http://127.0.0.1:9091` (originRequest giống `ws.onthilaixe.online`: keepAlive, disableChunkedEncoding).
- Event hiện có `PaymentSuccess` (`payment.success`) đủ; không đổi tên channel.
- SPA fallback poll 3s không gọi bank.

## Docker / mạng

App Laravel trong Docker **không** dùng `http://127.0.0.1:3999` (đó là loopback container).

- Histbank: `docker compose --profile serve up -d` tại `~/histbank`, join docker network `vps-internal`.
- Laravel: `HISTBANK_URL=http://tpbank-serve:3999`.
- Không publish histbank ra internet (bỏ map 0.0.0.0 nếu có). Chỉ network nội bộ.

## Bảo mật

- `POST /api/v1/orders/verify-payment` không được public: thêm middleware `histbank.secret` hoặc chỉ gọi nội bộ từ poller. Client không được tự đánh dấu paid.
- `markPaidTest` tắt khi `APP_ENV=production`.
- Không log APP_KEY, TPBank password, REVERB secret.

## Lỗi

| Trường hợp | Hành vi |
|---|---|
| histbank down | Poller log warning, không crash schedule; UI vẫn pending đến hết hạn |
| 423 AccountLocked | Backoff 15 phút, alert log |
| Hai đơn cùng mã (không thể) | unique order_code |
| CK sai nội dung | Không khớp, hết hạn như bình thường |
| CK đúng mã, sai tiền | Không khớp |
| Reverb down | SPA poll GET order status |

## Kiểm thử

- Unit: matcher (credit, regex, amount, dedupe).
- Feature: poller idle = 0 HTTP; poller match → paid + PaymentSuccess; create order trả VietQR URL; email optional; verify-payment 401 nếu thiếu secret.
- Không gọi TPBank thật trong CI (`Http::fake`).

## Tiêu chí xong

1. Tạo đơn ra QR VietQR với addInfo = mã `ORDFB…`.
2. Không pending → histbank không bị gọi.
3. Fake transaction khớp → order paid, event Reverb, mail nếu có email.
4. Reverb listen `127.0.0.1:9091`; `ws.tiemnhaduy.com` trên tunnel homelab.
5. Cửa sổ 12 phút; reconcile nightly không bỏ CK muộn.
