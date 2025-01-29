@props(['items'])
<div class="lg:relative">
    <div class="swiper h-auto" id="banner-slider">
        <div class="swiper-wrapper">
            @foreach ($items as $item)
                @if($item->photoUrl)
                    <a class="swiper-slide" href="{{route('vehicle', $item)}}">
                        <img src="{{ $item->photoUrl }}"
                             class="w-full aspect-video object-fill cursor-pointer"
                             alt="Banner {{ $loop->index + 1 }}">
                    </a>
                @endif
            @endforeach
        </div>
        <div class="swiper-scrollbar"></div>
    </div>

    <div class="hidden lg:block absolute top-[24rem] left-32 ">
        {{ $slot }}
    </div>
</div>
