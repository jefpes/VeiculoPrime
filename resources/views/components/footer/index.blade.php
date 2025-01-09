@props(['settings', 'stores'])
<footer class="bg-[var(--f-footer-background-color)] pt-8 mx-auto">
    <div class="container mx-auto w-full p-4 py-6">
        <div class="md:flex md:justify-between">
            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <x-footer.column title="Addresses">
                    @foreach ($stores as $s)
                        <x-footer.column.link href="#" text="{{ $s->fullAddress }}" />
                    @endforeach
                </x-footer.column>
                <x-footer.column title="Phones">
                    @foreach ($stores as $s)
                        @foreach ($s->phones as $p)
                            <x-footer.column.link href="#" text="{{ $p->fullNumber }}" />
                        @endforeach
                    @endforeach
                </x-footer.column>
                <x-footer.column title="Social">
                    <x-footer.social-links :settings="$settings" />
                </x-footer.column>
            </div>
        </div>

        <x-footer.divider />

        <div class="sm:flex sm:items-center sm:justify-between">
          <span class="text-sm text-[var(--f-text-variant-5)] sm:text-center">© {{ now()->year }}
              <a href="#" class="hover:underline">
                  {{ config('app.name') }}
              </a>. {{ trans('All Rights Reserved.') }}
          </span>
        </div>
    </div>
</footer>
