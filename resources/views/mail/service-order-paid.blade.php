<x-mail::message>
# Thanh toán thành công

Đã tắt phê duyệt bài viết thành công cho Facebook của bạn.

- Tên Facebook: **{{ $order->facebook_name ?: '—' }}**
- ID Facebook: **{{ $order->facebook_id ?: '—' }}**
- URL: {{ $order->facebook_profile_link }}

Đơn **{{ $order->order_code }}** đã được ghi nhận.

Số tiền: **{{ number_format($order->amount) }} VND**

Cảm ơn bạn đã thanh toán.

{{ config('app.name') }}
</x-mail::message>
