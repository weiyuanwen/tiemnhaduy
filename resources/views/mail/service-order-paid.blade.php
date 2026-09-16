<x-mail::message>
# Thanh toán thành công

Thanh toán đã được ghi nhận. Hệ thống đang tắt phê duyệt bài viết trên Facebook của bạn.

- Tên Facebook: **{{ $order->facebook_name ?: '—' }}**
- ID Facebook: **{{ $order->facebook_id ?: '—' }}**
- URL: {{ $order->facebook_profile_link }}

Đơn **{{ $order->order_code }}** đã được ghi nhận.

Số tiền: **{{ number_format($order->amount) }} VND**

Cảm ơn bạn đã thanh toán.

{{ config('app.name') }}
</x-mail::message>
