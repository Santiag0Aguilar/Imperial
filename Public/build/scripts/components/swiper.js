function InitSwiper() {
  const swiper = new Swiper(".swiper", {
    slidesPerView: 4,
    spaceBetween: 30,
    loop: true, // 🔁 Activa el loop infinito
    autoplay: {
      delay: 3000, // 3 segundos entre cada slide
      disableOnInteraction: false, // sigue auto después de moverlo manualmente
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
    breakpoints: {
      1024: {
        slidesPerView: 4,
      },
      768: {
        slidesPerView: 2, // 📱 Muestra 2 columnas en tablets
      },
      0: {
        slidesPerView: 2, // 📱 Muestra 2 columnas en móviles
      },
    },
  });
}

export default InitSwiper;
