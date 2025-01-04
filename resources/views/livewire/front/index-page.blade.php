<div>
    <section>
        <x-banner :emphasingVehicles="$emphasingVehicles">
            <x-banner.search-bar/>
        </x-banner>
    </section>

    {{-- <x-categories :categories="$categories"/> --}}

    @if($bestSellers)
        <x-best-sellers :bestSellers="$bestSellers"/>
    @endif

    <x-vehicles-home :vehicles="$vehicles"/>
</div>
