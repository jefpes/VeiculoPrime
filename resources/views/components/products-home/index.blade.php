@props(['products'])

<section class="container mx-auto mt-24">
    <x-products-home.title/>

    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 mt-4 px-4 md:px-0">
        @foreach($products as $product)
            @php
                $product = (object) $product;
            @endphp

            <x-products-home.card :product="$product"/>
        @endforeach
    </div>

    <div class="flex justify-center mt-8">
        <a href="{{ route('products') }}" class="rounded-md bg-[var(--f-primary-color)] w-full max-w-sm px-4 py-2 text-center text-sm font-medium tracking-wide text-[var(--f-neutral-100)] hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black active:opacity-100 active:outline-offset-0">
            {{ trans('View All') }}
        </a>
    </div>
</section>
