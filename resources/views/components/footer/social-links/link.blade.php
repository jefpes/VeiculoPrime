@props([
    'href' => '#',
    'text' => '',
])

<a href="{{ $href }}" class="text-[var(--f-text-variant-5)] hover:text-[var(--f-text-variant-9)]" target="_blank" rel="noopener noreferrer">
    {{$slot}}
    <span class="sr-only">
        {{ trans($text) }}
    </span>
</a>
