@props(['vehicle'])
<div class="lg:relative" x-data="{ imageModalIsOpen: false, image: '', openModal(image) { this.image = image; this.imageModalIsOpen = true; } }">
    <div class="swiper h-auto" id="product-banner-slider">
        <div class="swiper-wrapper">
            @php
                $banners = $vehicle->photos
                        ->where('public', true)
                        ->pluck('path')
                        ->toArray();
            @endphp
            @foreach ($banners as $banner)
                <div class="swiper-slide" @click="openModal('{{ image_path($banner) }}')">
                    <img src="{{ image_path($banner) }}" class="w-full aspect-video object-fill cursor-pointer"
                         alt="Banner {{ $loop->index + 1 }}">
                </div>
            @endforeach
        </div>
        <div class="swiper-scrollbar"></div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>

    <div x-cloak x-show="imageModalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="imageModalIsOpen" @keydown.esc.window="imageModalIsOpen = false" @click.self="imageModalIsOpen = false" class="fixed inset-0 z-30 flex items-center justify-center bg-black/20 p-4 backdrop-blur-md lg:p-8" role="dialog" aria-modal="true" aria-labelledby="videoModalTitle">
        <div x-show="imageModalIsOpen" x-transition:enter="transition ease-out duration-300 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="max-w-2xl w-full relative">
            <button type="button" x-show="imageModalIsOpen" @click="imageModalIsOpen = false" x-transition:enter="transition ease-out duration-200 delay-500" x-transition:enter-start="opacity-0 scale-0" x-transition:enter-end="opacity-100 scale-100" class="absolute -top-12 right-0 flex items-center justify-center rounded-full bg-neutral-50 p-1.5 text-neutral-900 hover:opacity-75 active:opacity-100 dark:bg-neutral-900 dark:text-white" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor" fill="none" stroke-width="1.4" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            <img class="w-full max-w-2xl rounded-md" :src="image"  alt=""/>
        </div>
    </div>
</div>
