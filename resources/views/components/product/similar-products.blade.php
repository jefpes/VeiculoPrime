@props(['products'])

<div class="mt-4">
    <h3 class="text-[var(--f-text-variant-8)] font-semibold text-lg">
        {{ trans('You may also like') }}
    </h3>

    <div class="swiper mt-4" id="similarProductsSlider">
        <div class="swiper-wrapper">
            @foreach($products as $product)
            <div class="swiper-slide">
                <x-products.product-card :product="$product" />
            </div>
            @endforeach
        </div>
    </div>
</div>
