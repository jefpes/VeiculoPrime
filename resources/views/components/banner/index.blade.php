@props(['emphasingVehicles'])
<div class="lg:relative">
    <div class="swiper h-auto" id="banner-slider">
        <div class="swiper-wrapper">
            @foreach ($emphasingVehicles as $vehicle)
                @if($vehicle->photoUrl)
                    <a class="swiper-slide" href="{{route('vehicle', $vehicle)}}">
                        <img src="{{ $vehicle->photoUrl }}"
                             class="w-full aspect-video object-fill"
                             alt="Banner {{ $loop->index + 1 }}">
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <div class="hidden lg:block absolute top-[16rem] left-32 ">
        {{ $slot }}
    </div>
</div>
