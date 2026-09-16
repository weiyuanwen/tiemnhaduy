@extends('layouts.rosta')

@section('title', 'Dự án nông sản Tiệm Nhà Duy | Chư Sê Gia Lai')
@section('meta_description', 'Các dự án trưng bày nông sản Tây Nguyên của Tiệm Nhà Duy: trang bán hàng, danh mục sản phẩm và quy trình đặt hàng.')
@section('og_title', 'Dự án Tiệm Nhà Duy')
@section('og_description', 'Xem các hạng mục Tiệm Nhà Duy đã làm để đưa cà phê và nông sản Chư Sê đến khách hàng.')
@section('og_image', asset('rosta/images/project-1.jpg'))
@section('og_image_alt', 'Dự án trưng bày nông sản Tiệm Nhà Duy')
@section('canonical_url', route('projects'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Dự án nông sản Tiệm Nhà Duy",
    "description": "Các dự án trưng bày nông sản Tây Nguyên của Tiệm Nhà Duy: trang bán hàng, danh mục sản phẩm và quy trình đặt hàng.",
    "url": "{{ route('projects') }}",
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
            "name": "Dự án",
            "item": "{{ route('projects') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'Dự án Tiệm Nhà Duy'])
    <div class="page-projects">
        <div class="container">
            <div class="row section-row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <p class="section-eyebrow wow fadeInUp">Hạng mục đã làm</p>
                        <h2 class="wow fadeInUp">Đưa nông sản Tây Nguyên đến đúng người cần</h2>
                    </div>
                </div>
            </div>
            <div class="row project-item-boxes align-items-center">
                <div class="col-lg-4 col-md-6 project-item-box">
                    <div class="project-item wow fadeInUp">
                        <div class="project-image">
                            <figure class="image-anime"><img src="{{ asset('rosta/images/project-1.jpg') }}" alt="Landing page nông sản Tiệm Nhà Duy"></figure>
                        </div>
                        <div class="project-content"><h3>Landing page nông sản</h3></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 project-item-box">
                    <div class="project-item wow fadeInUp" data-wow-delay="0.2s">
                        <div class="project-image">
                            <figure class="image-anime"><img src="{{ asset('rosta/images/project-2.jpg') }}" alt="Trang danh mục nông sản Tây Nguyên"></figure>
                        </div>
                        <div class="project-content"><h3>Trang danh mục sản phẩm</h3></div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 project-item-box">
                    <div class="project-item wow fadeInUp" data-wow-delay="0.4s">
                        <div class="project-image">
                            <figure class="image-anime"><img src="{{ asset('rosta/images/project-3.jpg') }}" alt="Giao diện quản lý đơn hàng nông sản"></figure>
                        </div>
                        <div class="project-content"><h3>UI quản lý đơn hàng</h3></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
