@props(['vehicle'])

@if($vehicle->extras->count() > 0)
    <div class="mt-2">
        <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
            {{ trans('Extras') }}
        </h3>

        <div class="grid grid-cols-2 gap-4">
            @foreach($vehicle->extras->chunk(ceil($vehicle->extras->count() / 2)) as $extras)
                <div>
                    @foreach($extras as $extra)
                        <div class="flex gap-2 my-3">
                            <div class="w-6 h-6">
                                {!! $extra->icon !!}
                            </div>
                            <span class="text-[var(--f-text-variant-6)] font-normal text-base ">
                            {{ trans($extra->name) }}
                        </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endif
