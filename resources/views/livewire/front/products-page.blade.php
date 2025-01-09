<div>
    <section class="container mx-auto px-4 md:px-0 pt-12">
        <x-vehicles.title />
        {{-- <x-vehicles.order-by /> --}}
        <x-vehicles.vehicles-list :vehicles="$this->vehicles" />
    </section>
</div>
