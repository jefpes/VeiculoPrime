@props([
    'categories' => []
])

<section class="container mx-auto mt-12">
    <x-categories.title/>

    <div class="flex mt-2 overflow-x-auto whitespace-nowrap">
        @foreach($categories as $category)
            @php
                $category = (object) $category;
            @endphp
            <x-categories.card :category="$category"/>
        @endforeach
    </div>
</section>
