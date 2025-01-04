@props(['brand', 'logo', 'url'])

<div class="mb-6 md:mb-0">
    <a href="{{$url}}" class="flex items-center">
        <img src="{{$logo}}" class="h-8 me-3" alt="{{$brand}} Logo" />
    </a>
</div>
