@extends('layouts.rosta')

@section('title', 'Sơ đồ trang | Tiệm Nhà Duy')
@section('meta_description', 'Danh sách đường dẫn chính của Tiệm Nhà Duy: trang chủ, sản phẩm, dịch vụ, liên hệ và chính sách.')
@section('og_title', 'Sơ đồ trang Tiệm Nhà Duy')
@section('og_description', 'Đường dẫn tiếng Việt tới các trang nông sản, dịch vụ và hỗ trợ của Tiệm Nhà Duy.')
@section('og_image', asset('rosta/images/page-header-bg.jpg'))
@section('og_image_alt', 'Sơ đồ trang website Tiệm Nhà Duy')
@section('canonical_url', route('sitemap'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Sơ đồ trang",
    "description": "Danh sách đường dẫn chính của Tiệm Nhà Duy: trang chủ, sản phẩm, dịch vụ, liên hệ và chính sách.",
    "url": "{{ route('sitemap') }}",
    "inLanguage": "vi-VN"
}
</script>
@endpush

@section('content')
    <style>
        #MainContent {padding-top: 110px;}
        .container.text-only {max-width: 900px; color: #000; padding-bottom: 90px;}
        .text-only h1 {font-size: 7vw; margin-bottom: 20px;}
        .sitemap-group {margin-bottom: 28px;}
        .sitemap-group h2 {font-size: 34px; margin-bottom: 12px;}
        .sitemap-group a {display: block; margin-bottom: 8px; color: #111; text-decoration: underline; text-underline-offset: 4px;}
        @media screen and (max-width: 800px) {
            #MainContent {padding-top: 80px;}
            .text-only h1 {font-size: 50px;}
            .sitemap-group h2 {font-size: 28px;}
        }
    </style>
    <div id="MainContent" tabindex="-1">
        <main data-header-color="dark">
            <div class="container text-only">
                <h1>Sơ đồ trang</h1>

                <div class="sitemap-group">
                    <h2>Trang chính</h2>
                    <a href="{{ route('home') }}">Trang chủ</a>
                    <a href="{{ route('about') }}">Về chúng tôi</a>
                    <a href="{{ route('services') }}">Dịch vụ</a>
                    <a href="{{ route('san-pham') }}">Sản phẩm</a>
                    <a href="{{ route('projects') }}">Dự án</a>
                    <a href="{{ route('contact') }}">Liên hệ</a>
                </div>

                <div class="sitemap-group">
                    <h2>Đường dẫn hỗ trợ</h2>
                    <a href="{{ route('thanh-toan') }}">Thanh toán QR</a>
                    <a href="{{ route('faqs') }}">Câu hỏi thường gặp</a>
                </div>

                <div class="sitemap-group">
                    <h2>Pháp lý</h2>
                    <a href="{{ route('privacy-policy') }}">Chính sách bảo mật</a>
                    <a href="{{ route('terms-of-service') }}">Điều khoản sử dụng</a>
                    <a href="{{ route('sitemap') }}">Sơ đồ trang</a>
                    <a href="{{ url('/sitemap.xml') }}">Sitemap XML</a>
                </div>
            </div>
        </main>
    </div>
@endsection
