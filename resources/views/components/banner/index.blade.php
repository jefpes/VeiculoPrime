@props(['emphasingVehicles'])
<div class="lg:relative">
    <div class="swiper" id="banner-slider">
        <div class="swiper-wrapper">
            @foreach ($emphasingVehicles as $vehicle)
                @if($vehicle->photoUrl)
                    <a class="swiper-slide" href="{{route('vehicle', $vehicle)}}">
                        <img src="{{ $vehicle->photoUrl }}"
                             class="w-full h-auto object-cover max-h-[750px]"
                             alt="Banner {{ $loop->index + 1 }}">
                    </a>
                @endif
            @endforeach
        </div>
    </div>

    <div class="hidden lg:block absolute top-[20rem] left-32 ">
        {{ $slot }}
    </div>
</div>
