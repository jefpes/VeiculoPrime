@props(['vehicle'])

@if($vehicle->accessories->count() > 0)
    <div class="mt-2">
        <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
            {{ trans('Accessories') }}
        </h3>

        <div class="grid grid-cols-2 gap-4">
            @foreach($vehicle->accessories->chunk(ceil($vehicle->accessories->count() / 2)) as $accessories)
                <div>
                    @foreach($accessories as $accessory)
                        <div class="flex gap-2 my-3">
                            @php
                                $image = Str::slug($accessory->name);
                                $image = 'icons/' . $image . '.svg';
                            @endphp

                            <img src="{{ asset($image) }}" class="w-6 h-6" alt="{{ $accessory->name }}">
                            <span class="text-[var(--f-text-variant-6)]">
                            {{ trans($accessory->name) }}
                        </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
