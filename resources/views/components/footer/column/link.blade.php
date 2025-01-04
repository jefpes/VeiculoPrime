@props(['href', 'text' => ''])

<li class="mb-4">
    <a href="{{$href}}" class="hover:underline">
        {{trans($text)}}
    </a>
</li>
