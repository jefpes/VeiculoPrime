import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import '../../../jeff-front/resources/css/sliders.css';

const bannerSlider = new Swiper("#banner-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
});

const productBannerSlider = new Swiper("#product-banner-slider", {
    centeredSlides: true,
    loop: true,
    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
    pagination: {
        el: ".swiper-pagination",
    },
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
