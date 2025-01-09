@props(['href', 'text' => ''])

<li class="mb-4">
    <a href="{{$href}}" class="hover:underline" target="_blank">
        {{trans($text)}}
    </a>
</li>
