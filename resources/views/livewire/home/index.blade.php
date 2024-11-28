<div x-data="{ showFilters: false }" class="space-y-8">
  <div class="flex justify-between items-center">
    <h1 class="text-3xl font-bold">{{ $company->name ?? 'Motor Market' }}</h1>
    <button @click="showFilters = !showFilters"
      class="flex items-center space-x-2 bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors duration-200">
      <span>{{ __('Filter') }}</span>
      <x-icons.filter class="w-5 h-5" />
    </button>
  </div>

  <!-- Modal de filtros -->
  <div x-show="showFilters" @click.away="showFilters = false" class="fixed inset-0 z-50 overflow-hidden"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    <div class="absolute inset-0 bg-gray-500 bg-opacity-75" @click="showFilters = false"></div>
    <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
      <div class="w-screen max-w-md">
        <div class="h-full flex flex-col bg-white shadow-xl overflow-y-scroll">
          <div class="p-6">
            <div class="mt-6 space-y-4">
              <!-- Botão Limpar Filtros -->
              <button wire:click="clearFilters"
                class="flex items-center space-x-2 w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 transition-colors duration-200">
                <span>{{ __('Clear Filter') }}</span>
              </button>
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
                    <label class="items-center pr-2">
                      <input wire:model.live="selectedBrands" :key="'brand-'.$b->id" type="checkbox" value="{{ $b->id }}" class="rounded text-indigo-600 shadow-sm focus:ring-indigo-500">
                      <span class="ms-1 text-sm">{{ $b->name }}</span>
                    </label>
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
      <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <a href="{{ route('show.v', $v->id) }}" class="block">
          <div class="h-56 w-full">
            <img src="{{ asset($v->photos->first()->path) }}" alt="{{ $v->model->name }}" class="object-fill w-full h-full">
          </div>
          <div class="p-4 space-y-4">
            <div class="flex justify-between items-center">
              <h2 class="text-xl font-semibold">{{ $v->model->name }}</h2>
              @if($v->promotional_price)
              <div class="text-right">
                <p class="text-sm text-gray-500 line-through">
                  <x-span-money :money="$v->sale_price" />
                </p>
                <p class="text-lg font-bold text-green-600">
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
                <p class="text-gray-500">{{ __('Brand') }}</p>
                <p class="font-medium">{{ $v->model->brand->name }}</p>
              </div>
              <div>
                <p class="text-gray-500">{{ __('Year') }}</p>
                <p class="font-medium">{{ $v->year_one.'/'.$v->year_two }}</p>
              </div>
              <div>
                <p class="text-gray-500">{{ __('KM') }}</p>
                <p class="font-medium">{{ number_format($v->km, 0, ',', '.') }}</p>
              </div>
              <div>
                <p class="text-gray-500">{{ __('Fuel') }}</p>
                <p class="font-medium">{{ $v->fuel }}</p>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
  </div>

  <div class="mt-8">
    {{ $this->vehicles->onEachSide(1)->links() }}
  </div>
</div>
