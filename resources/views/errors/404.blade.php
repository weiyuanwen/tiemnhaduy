@extends('layouts.rosta')

@section('title', 'Không tìm thấy trang | Tiệm Nhà Duy')
@section('meta_description', 'Trang bạn tìm không tồn tại. Quay lại trang chủ Tiệm Nhà Duy để xem cà phê Robusta và nông sản Tây Nguyên.')
@section('meta_robots', 'noindex,follow')
@section('og_title', 'Không tìm thấy trang')
@section('og_description', 'Đường dẫn này không còn. Hãy về trang chủ Tiệm Nhà Duy để tiếp tục mua nông sản Tây Nguyên.')
@section('og_image', asset('rosta/images/page-header-bg.jpg'))
@section('og_image_alt', 'Trang không tồn tại tại Tiệm Nhà Duy')

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "404 - Không tìm thấy trang",
    "description": "Trang bạn tìm không tồn tại tại Tiệm Nhà Duy.",
    "url": "{{ url()->current() }}",
    "inLanguage": "vi-VN"
}
</script>
@endpush

@section('content')
    <main id="main-content">
        <div class="page-header parallaxie">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="page-header-box">
                            <h1>Không tìm thấy trang</h1>
                            <nav class="wow fadeInUp">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">404</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="our-scrolling-ticker subpages-scrolling-ticker">
            <div class="scrolling-ticker-box">
                <div class="scrolling-content">
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Robusta</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Chư Sê Gia Lai</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản Tây Nguyên</span>
                </div>
                <div class="scrolling-content">
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Cà phê Robusta</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Mắc ca</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Tiêu đen</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Bơ sáp</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Sầu riêng</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Chư Sê Gia Lai</span>
                    <span><img src="{{ asset('rosta/images/asterisk-icon.svg') }}" alt="">Nông sản Tây Nguyên</span>
                </div>
            </div>
        </div>

        <div class="error-page">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="error-page-content">
                            <div class="section-title">
                                <h2 class="wow fadeInUp">Trang này không còn tồn tại</h2>
                            </div>
                            <div class="error-page-content-body">
                                <p class="wow fadeInUp" data-wow-delay="0.25s">
                                    Trang bạn đang tìm có thể đã bị đổi đường dẫn hoặc không còn tồn tại.
                                    Hãy quay về trang chủ để tiếp tục trải nghiệm.
                                </p>
                                <a class="btn-default wow fadeInUp" data-wow-delay="0.5s" href="{{ url('/') }}">
                                    <span>Quay về trang chủ</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
