@props([
    'settings',
    'href' => '#'
   ])

<a href="{{$href}}" class="text-2xl font-bold text-[var(--f-neutral-900)]" id="app-header-application-logo">
    @if($settings->logo)
        <img src="{{ image_path($settings->logo) }}"
             alt="Application Logo"
             class="w-32 h-6"
        />
    @else
        <h1 class="text-lg font-bold text-[var(--f-neutral-900)]">
            {{ $settings->name ?? 'Motor Market' }}
        </h1>
    @endif
</a>
