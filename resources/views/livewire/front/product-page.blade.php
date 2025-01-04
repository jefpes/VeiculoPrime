<div>
    <x-product.banners :product="$product"/>

    <section class="container mt-6 mx-auto px-4">
        <x-product.header :product="$product"/>
        <x-product.title :product="$product"/>

        <x-product.header-details :product="$product"/>

        <x-product.location :product="$product"/>

        <x-product.price :product="$product" :paymentMethods="$paymentMethods"/>

        <x-product.about :product="$product"/>

        <x-product.characteristics :product="$product"/>

        <x-product.similar-products :products="$similarProducts"/>
    </section>
</div>
