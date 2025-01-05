@props(['brand', 'settings', 'url'])

<div class="mb-6 md:mb-0">
    <a href="{{$url}}" class="flex items-center">
        @if($settings->logo)
            <img src="{{ image_path($settings->logo) }}" alt="{{$brand}} Logo" class="h-8 me-3" />
        @else
            <h1 class="text-lg font-bold text-[var(--f-neutral-900)]">
                {{ $settings->name ?? 'Motor Market' }}
            </h1>
        @endif
    </a>
</div>
