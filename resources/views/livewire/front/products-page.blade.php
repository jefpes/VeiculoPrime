<div>
    <section class="container mx-auto px-4 md:px-0 pt-12">
        <x-products.title />
        <x-products.order-by />
        <x-products.products-list :products="$products" />
    </section>
</div>
