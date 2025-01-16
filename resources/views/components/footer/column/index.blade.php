@props(['title'])

<div>
    <h2 class="mb-6 text-sm font-semibold text-[var(--f-text-variant-9)] uppercase">
        {{ trans($title) }}
    </h2>
    <ul class="text-[var(--f-text-variant-5)] font-medium">
        {{$slot}}
    </ul>
</div>
