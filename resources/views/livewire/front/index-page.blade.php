<div>
    <section>
        <x-banner :banners="$banners">
            <x-banner.search-bar/>
        </x-banner>
    </section>

    {{-- <x-categories :categories="$categories"/> --}}

    @if($mostSearched)
        <x-most-searched :mostSearched="$mostSearched"/>
    @endif

    <x-products-home :products="$products"/>
</div>
