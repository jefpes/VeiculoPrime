<div>
    <section>
        <div class="lg:relative">
            <div class="swiper h-auto" id="store-slider">
                <div class="swiper-wrapper">
                    @foreach ($banners as $item)
                        @if($item->path && Storage::disk('public')->exists($item->path))
                            <img src="{{ image_path($item->path) }}" class="swiper-slide w-full aspect-video object-fill"
                                alt="Banner {{ $loop->index + 1 }}">
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto mt-24">
       {!! str($about)->markdown()->sanitizeHtml() !!}
    </section>
</div>
