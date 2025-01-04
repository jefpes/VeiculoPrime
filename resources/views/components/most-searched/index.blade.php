@props(['mostSearched'])

<section class="container mx-auto mt-24">
    <x-most-searched.title/>

    <div class="flex mt-2 overflow-x-auto whitespace-nowrap">
        @foreach($mostSearched as $item)
            @php
                $item = (object) $item;
            @endphp

            <x-most-searched.card :item="$item"/>
        @endforeach
    </div>
</section>
