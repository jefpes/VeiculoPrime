@props(['settings', 'stores'])
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
            <div class="md:flex space-x-2 md:space-y-2 md:flex-auto md:justify-between ">
                @php
                   $tenant = \App\Models\Tenant::all();
                @endphp

            </div>
        @endif
        <x-footer.divider />

        <div class="flex items-center justify-center">
          <span class="text-sm text-[var(--f-text-variant-5)] sm:text-center">© {{ now()->year }}
              <a href="#" class="hover:underline">
                  {{ config('app.name') }}
              </a>. {{ trans('All Rights Reserved.') }}
          </span>
        </div>
    </div>
</footer>
