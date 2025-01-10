@props([
    'text' => '',
    'href' => '#',
    'bold' => false
  ])

<li class="mt-4 w-full border-none">
    <a href="{{ $href }}"
        @class([
            'rounded-md bg-[var(--f-primary-color)] px-4 py-2 block text-center font-medium tracking-wide text-[var(--f-neutral-100)] hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0',
        ]) >
        {{ trans($text) }}
    </a>
</li>
