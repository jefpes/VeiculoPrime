import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import '../css/sliders.css';

const bannerSlider = new Swiper("#banner-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    speed: 1500,
});

const productBannerSlider = new Swiper("#product-banner-slider", {
    centeredSlides: true,
    loop: true,
    slidesPerView: 1,
    pagination: {
        el: ".swiper-pagination",
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    breakpoints: {
        640: {
            slidesPerView: 3,
            spaceBetween: 5,
        }
    }
});

const similarProductsSlider = new Swiper("#similarProductsSlider", {
    loop: true,
    slidesPerView: 1,
    spaceBetween: 10,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    breakpoints: {
        640: {
            slidesPerView: 2,
            spaceBetween: 10,
        },
        768: {
            slidesPerView: 3,
            spaceBetween: 10,
        },
    },
});
