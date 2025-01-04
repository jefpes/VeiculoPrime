@props([
    'text' => '',
    'href' => '#',
    'bold' => false
])
<li class="py-4">
    <a href="{{ $href }}"
       @class([
           'w-full text-lg text-black focus:underline',
           'font-bold' => $bold
       ])
       aria-current="page">
        {{ trans($text) }}
    </a>
</li>
