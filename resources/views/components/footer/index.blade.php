@props(['settings', 'stores', 'tenants'])
<footer class="bg-[var(--f-footer-background-color)] pt-8 mx-auto">
    <div class="container mx-auto w-full p-4 py-6">
        @if (tenant())
            <div class="md:flex space-x-2 md:space-y-2 md:flex-auto md:justify-between ">
                <x-footer.column title="Addresses">
                    @foreach ($stores as $s)
                        <x-footer.column.link :href="$s->gerarLinkGoogleMaps()" text="{{ $s->fullAddress }}" />
                    @endforeach
                </x-footer.column>
                <x-footer.column title="Phones">
                    @foreach ($stores as $s)
                        @foreach ($s->phones as $p)
                            <x-footer.column.link href="{{ $p->gerarLinkWhatsApp() }}" text="{{ $p->fullNumber }}" />
                        @endforeach
                    @endforeach
                </x-footer.column>
                @if ($settings->facebook || $settings->instagram || $settings->twitter || $settings->linkedin || $settings->youtube || $settings->whatsapp)
                    <x-footer.column title="Social">
                        <x-footer.social-links :settings="$settings" />
                    </x-footer.column>
                @endif
            </div>
            @else
            <div class="md:flex space-x-2 md:space-y-2">
                <div class="w-full">
                <h2 class="mb-4 text-md font-semibold text-[var(--f-text-variant-9)] uppercase"> Lojas Parceiras </h2>
                    <ul class="text-[var(--f-text-variant-5)] text-2xl flex flex-wrap gap-2">
                        @foreach ($tenants as $t)
                            <a href="{{ $t->getTenantUrl() }}" target="_blank">
                                @if ($t->setting->logo && Storage::disk('public')->exists($t->setting->logo))
                                    <img class="object-fill max-w-80 max-h-12 rounded-t-lg" src="{{ image_path($t->setting->logo) }}" alt=""/>
                                @else
                                    <li class="hover:underline"> {{ $t->name }} </li>
                                @endif
                            </a>
                        @endforeach
                    </ul>
                </div>
                @if ($settings->facebook || $settings->instagram || $settings->twitter || $settings->linkedin || $settings->youtube || $settings->whatsapp)
                    <x-footer.column title="Social">
                        <x-footer.social-links :settings="$settings" />
                    </x-footer.column>
                @endif
            </div>
        @endif
        <x-footer.divider />

        <div class="flex items-center justify-center">
            <a class="text-sm text-[var(--f-text-variant-5)] hover:text-[var(--f-text-variant-10)] sm:text-center hover:underline" href="{{ config('app.url') }}">© {{ now()->year }} {{ config('app.name') }} . {{ trans('All Rights Reserved.') }}</a>
        </div>
    </div>
</footer>
