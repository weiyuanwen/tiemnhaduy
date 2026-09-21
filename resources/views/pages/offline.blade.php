@extends('layouts.rosta')

@section('title', 'Mất kết nối internet | Tiệm Nhà Duy')
@section('meta_description', 'Bạn đang ngoại tuyến. Kiểm tra mạng rồi quay lại Tiệm Nhà Duy để xem nông sản Tây Nguyên.')
@section('meta_robots', 'noindex,follow')
@section('og_title', 'Mất kết nối internet')
@section('og_description', 'Bạn đang ngoại tuyến. Kiểm tra mạng rồi quay lại Tiệm Nhà Duy.')
@section('canonical_url', route('offline'))

@section('content')
    <main id="main-content">
        <div class="page-header parallaxie">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <div class="page-header-box">
                            <h1>Mất kết nối internet</h1>
                            <nav class="wow fadeInUp">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Trang chủ</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Ngoại tuyến</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="error-page">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="error-page-content">
                            <div class="section-title">
                                <h2 class="wow fadeInUp">Bạn đang ngoại tuyến</h2>
                            </div>
                            <div class="error-page-content-body">
                                <p class="wow fadeInUp" data-wow-delay="0.25s">
                                    Kiểm tra kết nối mạng rồi tải lại trang để tiếp tục xem cà phê Robusta và nông sản Tây Nguyên.
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
