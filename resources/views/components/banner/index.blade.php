@props(['emphasingVehicles'])
<div class="lg:relative">
    <div class="swiper" id="banner-slider">
        <div class="swiper-wrapper">
            @foreach ($emphasingVehicles as $vehicle)
                <div class="swiper-slide">
                    <img src="{{ $vehicle->photoUrl }}" class="w-full h-auto object-cover max-h-[650px]" alt="Banner {{ $loop->index + 1 }}">
                </div>
            @endforeach
        </div>
    </div>

    <div class="hidden lg:block absolute top-[25rem] left-32 ">
        {{ $slot }}
    </div>
</div>
