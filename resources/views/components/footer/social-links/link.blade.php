@props([
    'href' => '#',
    'text' => '',
])

<a href="{{$href}}" class="text-[var(--f-text-variant-5)] hover:text-[var(--f-text-variant-9)] ms-5">
    {{$slot}}
    <span class="sr-only">
        {{ trans($text)  }}
    </span>
</a>
