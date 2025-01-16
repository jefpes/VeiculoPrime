<div>
    <section>
        <x-banner :items="$emphasingVehicles" />
    </section>

    {{-- <x-categories :categories="$categories"/> --}}

    {{-- @if($bestSellers)
        <x-best-sellers :bestSellers="$bestSellers"/>
    @endif --}}

    <x-vehicles-home :vehicles="$vehicles"/>
</div>
