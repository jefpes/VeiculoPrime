@props([
    'logo',
    'href' => '#'
   ])

<a href="{{$href}}" class="text-2xl font-bold text-[var(--f-neutral-900)]" id="app-header-application-logo">
    <img src="{{ asset($logo) }}"
         alt="Application Logo"
         class="w-32 h-6"
    />
</a>
