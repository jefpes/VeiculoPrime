@props(['vehicle'])

<div class="md:flex justify-between">
    <h4 class="flex flex-col my-2 text-2xl font-bold tracking-tight text-[var(--f-text-variant-5)] justify-between">
        <span class="">{{ strtoupper($vehicle->model->brand->name) }} {{strtoupper($vehicle->model->name)}}</span>
    </h4>
    @if (!tenant())
        <a href="{{ $vehicle->store->tenant->getTenantUrl() }}" class="mt-4 text-2xl font-bold text-[var(--f-neutral-900)]" id="app-header-application-logo">
            @if ($vehicle->store->tenant->setting->logo && Storage::disk('public')->exists($vehicle->store->tenant->setting->logo))
                <img src="{{ image_path($vehicle->store->tenant->setting->logo) }}"
                alt="Application Logo"
                class="max-w-[10rem] "
                />
            @else
                <h1 class="text-lg font-bold text-[var(--f-neutral-900)]">
                    {{ $settings->name ?? 'Motor Market' }}
                </h1>
            @endif
        </a>
    @endif
</div>
