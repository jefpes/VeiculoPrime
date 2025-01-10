import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import '../css/sliders.css';

const bannerSlider = new Swiper("#banner-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    scrollbar: {
        el: ".swiper-scrollbar",
        hide: false,
      },
    breakpoints: {
        640: {
            slidesPerView: 1,
            spaceBetween: 1,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 1,
        },
    },
    speed: 2000,
});

const productBannerSlider = new Swiper("#product-banner-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    slidesPerView: 1,
    scrollbar: {
        el: ".swiper-scrollbar",
        hide: false,
      },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        640: {
            slidesPerView: 3,
            spaceBetween: 1,
        }
    }
});

const similarProductsSlider = new Swiper("#similarProductsSlider", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 4,
            spaceBetween: 10,
        },
    },
});

const storeSlider = new Swiper("#store-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 3000,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: {
            slidesPerView: 1,
            spaceBetween: 1,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 1,
        },
    },
    speed: 2000,
});
