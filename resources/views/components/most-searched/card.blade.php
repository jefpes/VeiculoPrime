@props([
    'item' => []
])

<div class="relative inline-block p-2 h-[18.125rem] lg:h-[14.5rem] rounded-lg flex-shrink-0">
    <a href="{{$item->id}}" class="block h-full rounded-lg">
        <img src="https://placehold.co/600x400?text={{ $item->name }}" class="object-cover w-full h-full rounded-lg"/>

        <h3 class="absolute flex flex-col text-lg font-bold top-6 left-16 text-[var(--f-neutral-700)]">
            <span>{{ strtoupper($item->brand->name) }}</span>
            <strong class="-mt-2 text-[var(--f-tertiary-color)]">
                {{ strtoupper($item->name) }}
            </strong>
        </h3>
    </a>
</div>
