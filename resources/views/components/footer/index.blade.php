@props(['settings'])
<footer class="bg-[var(--f-footer-background-color)] pt-8 mx-auto">
    <div class="container mx-auto w-full p-4 py-6 lg:py-8">
        <div class="md:flex md:justify-between">
            <x-footer.brand-logo brand="laravel" url="#" logo="{{$settings->logo}}" />

            <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-3">
                <x-footer.column title="Column 1">
                    <x-footer.column.link href="#" text="Link 1" />
                    <x-footer.column.link href="#" text="Link 2" />
                </x-footer.column>
                <x-footer.column title="Column 2">
                    <x-footer.column.link href="#" text="Link 1" />
                    <x-footer.column.link href="#" text="Link 2" />
                </x-footer.column>
                <x-footer.column title="Column 3">
                    <x-footer.column.link href="#" text="Link 1" />
                    <x-footer.column.link href="#" text="Link 2" />
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
            <x-footer.social-links :settings="$settings" />
        </div>
    </div>
</footer>
