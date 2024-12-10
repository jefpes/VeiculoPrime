<div x-data="{ showFilters: false }" class="space-y-8">
  <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold">{{ $company->name ?? 'Motor Market' }}</h1>
    <x-primary-button @click="showFilters = !showFilters">
      <span>{{ __('Filter') }}</span>
      <x-icons.filter class="w-5 h-5" />
    </x-primary-button>
  </div>

  <!-- Modal de filtros -->
  <div x-show="showFilters" @click.away="showFilters = false" class="fixed inset-0 z-50 overflow-hidden"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-gray-500 bg-opacity-75" @click="showFilters = false"></div>
    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
      <div class="w-screen max-w-md">
        <div class="h-full flex flex-col shadow-xl overflow-y-scroll" style="{{ 'background-color:' . $company?->body_bg_color }}">
          <div class="p-6">
            <!-- Botão Limpar Filtros -->
            <div class="mt-6 space-y-4">
                <div class="flex gap-x-2">
                    <x-danger-button wire:click="clearFilters">
                        <span>{{ __('Clear Filters') }}</span>
                    </x-danger-button>
                    <x-secondary-button @click="showFilters = false">
                        <span>{{ __('Close') }}</span>
                    </x-secondary-button>
                </div>
              <div >
                <x-select wire:model.live="vehicle_type_id" class="w-full" label="Type">
                  <option value="">{{ __('All') }}</option>
                  @foreach ($this->types as $t)
                  <option value="{{ $t->id }}">{{ $t->name }}</option>
                  @endforeach
                </x-select>
              </div>

              <div class="space-y-2">
                <h3 class="text-lg font-semibold">{{ __('Brands') }}</h3>
                <div class="w-full list-decimal list-inside space-2">
                  @foreach ($this->brands as $b)
                  <div class="inline-flex">
                    <div class="flex items-center mb-4">
                        <input id="checkbox-{{ $b->id }}" type="checkbox" wire:model.live="selectedBrands" :key="'brand-'.$b->id" value="{{ $b->id }}" class="w-4 h-4 border-gray-300 rounded focus:ring-2" style="{{ 'color:' . $company?->check_text_color . '; background-color:' . $company?->check_color }}">
                        <label for="checkbox-{{ $b->id }}" class="ms-2 text-sm font-medium" style="{{ 'color:' . $company?->check_text_color }}">{{ $b->name }}</label>
                    </div>
                  </div>
                  @endforeach
                </div>
              </div>

              <div class="space-y-2">
                <h3 class="text-lg font-semibold">{{ __('Price') }}</h3>
                <x-select wire:model.live="order" label="Order" class="w-full">
                  <option value="asc">{{ __('Growing') }}</option>
                  <option value="desc">{{ __('Descending') }}</option>
                </x-select>
                <x-select wire:model.live="max_price" class="w-full" label="Max Price">
                  <option value="">{{ __('All') }}</option>
                  @foreach ($this->prices as $p)
                  <option value="{{ (int) round($p->sale_price) }}">
                    <x-span-money :money="$p->sale_price" />
                  </option>
                  @endforeach
                </x-select>
              </div>

              <div class="space-y-2">
                <h3 class="text-lg font-semibold">{{ __('Year') }}</h3>
                <x-select wire:model.live="year_ini" class="w-full" label="Year Initial">
                  <option value="">{{ __('Select') }}</option>
                  @foreach ($this->years as $y)
                  <option value="{{ $y->year_one }}">{{ $y->year_one }}</option>
                  @endforeach
                </x-select>
                <x-select wire:model.live="year_end" class="w-full" label="Year Final">
                  <option value="">{{ __('Select') }}</option>
                  @foreach ($this->years as $y)
                  <option value="{{ $y->year_one }}">{{ $y->year_one }}</option>
                  @endforeach
                </x-select>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Lista de veículos -->
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @foreach ($this->vehicles as $v)
      <div class="md:rounded-lg shadow-md overflow-hidden" style="{{ 'background-color:' . $company->card_color . ';color:' . $company->card_text_color }}">
        <a href="{{ url('/show', $v->id) }}" class="block">
          <div class="h-56 w-full">
            @php
                $mainPhoto = $v->photos->where('main', 1)->first();
                $defaultPhoto = $v->photos->first();
            @endphp

            @if ($defaultPhoto && Storage::disk('public')->exists($defaultPhoto->path))
                <img src="{{ image_path(($mainPhoto->path ?? $defaultPhoto->path)) }}" alt="{{ $v->model->name }}" class="object-fill w-full h-full">
            @else
                <x-icons.no-image class="object-fill w-full h-full" />
            @endif
          </div>
          <div class="py-2 px-3 space-y-2">
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold">{{ $v->model->name }}</h2>
              @if($v->promotional_price)
              <div class="text-right">
                <p class="text-sm line-through" style="opacity: 0.6">
                  <x-span-money :money="$v->sale_price" />
                </p>
                <p class="text-lg font-bold" style="{{ 'color:' . $company->promo_price_color }}">
                  <x-span-money :money="$v->promotional_price" />
                </p>
              </div>
              @else
              <p class="text-lg font-bold">
                <x-span-money :money="$v->sale_price" />
              </p>
              @endif
            </div>
            <div class="grid grid-cols-2 gap-2 text-sm">
              <div>
                <p class="font-bold">{{ __('Brand') }}</p>
                <p class="font-medium">{{ $v->model->brand->name }}</p>
              </div>
              <div>
                <p class="font-bold">{{ __('Year') }}</p>
                <p class="font-medium">{{ $v->year_one.'/'.$v->year_two }}</p>
              </div>
              <div>
                <p class="font-bold">{{ __('KM') }}</p>
                <p class="font-medium">{{ number_format($v->km, 0, ',', '.') }}</p>
              </div>
              <div>
                <p class="font-bold">{{ __('Fuel') }}</p>
                <p class="font-medium">{{ $v->fuel }}</p>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  <div class="mt-8">
    {{ $this->vehicles->links('livewire::simple-tailwind') }}
  </div>
</div>
