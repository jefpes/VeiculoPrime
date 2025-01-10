@props(['bestSellers'])

<section class="container mx-auto mt-24">
    <x-best-sellers.title/>

    <div class="flex mt-2 overflow-x-auto whitespace-nowrap">
        @foreach($bestSellers as $vehicleModel)
            @php
                $vehicleModel = (object) $vehicleModel;
            @endphp

            <x-best-sellers.card :vehicleModel="$vehicleModel"/>
        @endforeach
    </div>
</section>
