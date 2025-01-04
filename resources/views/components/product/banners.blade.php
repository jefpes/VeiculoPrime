<div class="lg:relative">
    <div class="swiper" id="product-banner-slider">
        <div class="swiper-wrapper">
            @foreach ($banners as $banner)
                <div class="swiper-slide">
                    <img src="{{ $banner }}" class="w-full h-auto object-cover max-h-[550px]"
                         alt="Banner {{ $loop->index + 1 }}">
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
