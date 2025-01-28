@props([ 'settings' ])

<a href="{{ url('/') }}" class="text-2xl font-bold text-[var(--f-neutral-900)]" id="app-header-application-logo">
    @if ($settings->logo && Storage::disk('public')->exists($settings->logo))
        <img src="{{ image_path($settings->logo) }}"
             alt="Application Logo"
             class="max-w-[10rem] -my-2"
        />
    @else
        <h1 class="text-lg font-bold text-[var(--f-neutral-900)]">
            {{ $settings->name ?? 'Veiculo Prime' }}
        </h1>
    @endif
</a>
