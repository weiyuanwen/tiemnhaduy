@extends('layouts.rosta')

@section('title', 'Về Tiệm Nhà Duy – nông sản Chư Sê, Gia Lai')
@section('meta_description', 'Tiệm Nhà Duy mang cà phê Robusta Gia Lai và nông sản sạch từ Chư Sê đến gia đình Việt. Nguồn gốc rõ, hương vị thật của Tây Nguyên.')
@section('og_title', 'Về Tiệm Nhà Duy')
@section('og_description', 'Câu chuyện nông sản Chư Sê – Gia Lai: cà phê Robusta, mắc ca, tiêu đen và bơ sáp từ vườn đến bàn ăn.')
@section('og_image', asset('rosta/images/our-story-image.jpg'))
@section('og_image_alt', 'Câu chuyện Tiệm Nhà Duy và nông sản Tây Nguyên')
@section('canonical_url', route('about'))

@push('structured_data')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "WebPage",
    "name": "Về Tiệm Nhà Duy – nông sản Chư Sê, Gia Lai",
    "description": "Tiệm Nhà Duy mang cà phê Robusta Gia Lai và nông sản sạch từ Chư Sê đến gia đình Việt. Nguồn gốc rõ, hương vị thật của Tây Nguyên.",
    "url": "{{ route('about') }}",
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
            "name": "Về chúng tôi",
            "item": "{{ route('about') }}"
        }
    ]
}
</script>
@endpush

@section('content')
    @include('pages.partials.rosta.page-header', ['title' => 'Về Tiệm Nhà Duy'])
    <div class="about-us">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about-us-content">
                        <div class="section-title">
                            <p class="section-eyebrow wow fadeInUp">Câu chuyện thương hiệu</p>
                            <h2 class="wow fadeInUp">Mang nông sản sạch đến mọi gia đình Việt</h2>
                            <p class="wow fadeInUp" data-wow-delay="0.2s">Tiệm Nhà Duy tập trung vào nguồn gốc rõ ràng, sản phẩm an toàn và trải nghiệm mua sắm minh bạch.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <figure class="image-anime">
                        <img class="media-kenburns-img" src="{{ asset('rosta/images/produce/phin-coffee.webp') }}" alt="Pha cà phê phin Tây Nguyên" width="1200" height="796" loading="lazy" decoding="async">
                    </figure>
                </div>
            </div>
        </div>
    </div>
@endsection
