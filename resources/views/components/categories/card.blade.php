@props([
    'category' => []
])

<div class="relative inline-block p-2 h-[18.125rem] lg:h-[14.5rem] rounded-lg flex-shrink-0">
    <a href="{{$category->url}}" class="block h-full rounded-lg">
        <img src="{{ $category->image }}" alt="Category"
             class="w-full h-full object-cover rounded-lg"/>

        <h3 class="absolute bottom-6 left-8 text-[var(--f-text-variant-1)] font-bold text-2xl">
            {{ $category->name }}
        </h3>
    </a>
</div>
