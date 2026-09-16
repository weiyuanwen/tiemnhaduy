@extends('layouts.rosta')

@section('title', 'Điều khoản sử dụng | Tiệm Nhà Duy')
@section('meta_description', 'Điều khoản khi xem website, đặt hàng cà phê và nông sản Tây Nguyên tại Tiệm Nhà Duy.')
@section('og_title', 'Điều khoản sử dụng | Tiệm Nhà Duy')
@section('og_description', 'Quy định đặt hàng, thanh toán, giao hàng và sử dụng nội dung trên website Tiệm Nhà Duy.')
@section('og_image', asset('rosta/images/page-header-bg.jpg'))
@section('og_image_alt', 'Điều khoản sử dụng website Tiệm Nhà Duy')
@section('canonical_url', route('terms-of-service'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Điều khoản sử dụng",
    "description": "Điều khoản khi xem website, đặt hàng cà phê và nông sản Tây Nguyên tại Tiệm Nhà Duy.",
    "url": "{{ route('terms-of-service') }}",
    "inLanguage": "vi-VN"
}
</script>
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@@type": "ListItem",
            "position": 1,
            "name": "Trang chủ",
            "item": "{{ route('home') }}"
        },
        {
            "@@type": "ListItem",
            "position": 2,
            "name": "Điều khoản sử dụng",
            "item": "{{ route('terms-of-service') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    <style>
        #MainContent {padding-top: 110px;}
        .shopify-policy__container {max-width: 860px; margin: 0 auto; padding: 0 15px 90px; color: #000;}
        .shopify-policy__title h1 {font-size: 64px; line-height: 1.1; margin-bottom: 24px; text-transform: none;}
        .shopify-policy__body h2 {font-size: 22px; margin: 28px 0 10px; font-family: "Montserrat", sans-serif; font-weight: 600; text-transform: none;}
        .shopify-policy__body .rte p {margin-bottom: 16px; line-height: 1.75;}
        @media screen and (max-width: 800px) {
            #MainContent {padding-top: 80px;}
            .shopify-policy__title h1 {font-size: 42px;}
        }
    </style>

    <div id="MainContent" tabindex="-1">
        <div class="shopify-policy__container">
            <div class="shopify-policy__title">
                <h1>Điều khoản sử dụng</h1>
            </div>
            <div class="shopify-policy__body">
                <div class="rte">
                    <h2>Chấp nhận điều khoản</h2>
                    <p>Khi truy cập và sử dụng website Tiệm Nhà Duy, bạn đồng ý tuân thủ các điều khoản được nêu tại trang này.</p>
                    <h2>Phạm vi dịch vụ</h2>
                    <p>Website cung cấp thông tin sản phẩm, đặt hàng trực tuyến và các tiện ích hỗ trợ khách hàng. Chúng tôi có quyền điều chỉnh nội dung, tính năng và giá bán mà không cần thông báo trước.</p>
                    <h2>Tài khoản và thông tin người dùng</h2>
                    <p>Khách hàng chịu trách nhiệm về tính chính xác của thông tin cung cấp khi đặt hàng và bảo mật thông tin tài khoản của mình.</p>
                    <h2>Đặt hàng và thanh toán</h2>
                    <p>Đơn hàng chỉ được xác nhận khi thông tin đầy đủ và thanh toán hợp lệ theo phương thức được hỗ trợ. Chúng tôi có quyền từ chối các đơn hàng có dấu hiệu gian lận hoặc sai thông tin.</p>
                    <h2>Giao hàng và đổi trả</h2>
                    <p>Thời gian giao hàng phụ thuộc khu vực và đơn vị vận chuyển. Chính sách đổi trả áp dụng theo quy định công bố tại từng thời điểm và theo điều kiện sản phẩm.</p>
                    <h2>Quyền sở hữu trí tuệ</h2>
                    <p>Toàn bộ nội dung trên website bao gồm hình ảnh, văn bản, logo, thiết kế thuộc quyền sở hữu của Tiệm Nhà Duy hoặc đối tác được cấp phép. Nghiêm cấm sao chép, phát tán khi chưa được chấp thuận bằng văn bản.</p>
                    <h2>Hành vi bị cấm</h2>
                    <p>Không sử dụng website cho mục đích vi phạm pháp luật, phát tán mã độc, thu thập dữ liệu trái phép hoặc gây ảnh hưởng đến hệ thống vận hành.</p>
                    <h2>Giới hạn trách nhiệm</h2>
                    <p>Tiệm Nhà Duy không chịu trách nhiệm cho các thiệt hại gián tiếp phát sinh từ việc gián đoạn dịch vụ, lỗi mạng, hoặc sự kiện bất khả kháng ngoài khả năng kiểm soát hợp lý.</p>
                    <h2>Liên kết bên thứ ba</h2>
                    <p>Một số liên kết có thể dẫn đến website bên thứ ba. Chúng tôi không chịu trách nhiệm cho nội dung và chính sách tại các website đó.</p>
                    <h2>Điều chỉnh điều khoản</h2>
                    <p>Chúng tôi có quyền cập nhật điều khoản sử dụng khi cần thiết. Phiên bản mới nhất có hiệu lực ngay khi được đăng trên website.</p>
                    <h2>Thông tin liên hệ</h2>
                    <p>Mọi thắc mắc liên quan điều khoản sử dụng vui lòng gửi về <a href="mailto:support@tiemnhaduy.com">support@tiemnhaduy.com</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
