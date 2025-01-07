<div>
    <x-vehicle.banners :vehicle="$vehicle"/>

    <section class="container mt-6 mx-auto px-4">
        <x-vehicle.header :vehicle="$vehicle"/>
        <x-vehicle.title :vehicle="$vehicle"/>

        {{--        <x-vehicle.header-details :vehicle="$vehicle"/>--}}

        <x-vehicle.location :vehicle="$vehicle"/>

        <x-vehicle.price :vehicle="$vehicle" :paymentMethods="$paymentMethods"/>

        <x-vehicle.characteristics :vehicle="$vehicle"/>

        <x-vehicle.accessories :vehicle="$vehicle"/>

        <x-vehicle.extras :vehicle="$vehicle"/>

        <x-vehicle.similar-vehicles :vehicles="$similarVehicles"/>
    </section>
</div>
