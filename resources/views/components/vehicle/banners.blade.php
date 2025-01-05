@props(['vehicle'])
<div class="lg:relative">
    <div class="swiper" id="product-banner-slider">
        <div class="swiper-wrapper">
            @php
                $banners = $vehicle->photos
                        ->where('public', true)
                        ->pluck('path')
                        ->toArray();
            @endphp
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <img src="{{ image_path($banner) }}" class="w-full h-auto object-cover max-h-[550px]"
                         alt="Banner {{ $loop->index + 1 }}">
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
