/*
function setWeddingBlockAspectRatio() {
    const weddingBlocks = document.querySelectorAll('.wedding-block');
    weddingBlocks.forEach(block => {
      const width = block.offsetWidth;
      const blockHeight = width * 0.60;
      block.style.height = `${blockHeight}px`;
    });
  }
  
  // Вызываем функцию при загрузке страницы и при изменении размера окна
  window.addEventListener('load', setWeddingBlockAspectRatio);
  window.addEventListener('resize', setWeddingBlockAspectRatio);
  */

//SWIPER
document.addEventListener("DOMContentLoaded", function () {
  const sliderEl = document.querySelector(".cake-slider.swiper");

  if (!sliderEl) return;

  var swiper = new Swiper(sliderEl, {
    loop: true,
    slidesPerView: 5,
    centeredSlides: true,
    autoplay: {
      delay: 3000,
      disableOnInteraction: false,
    },
    breakpoints: {
      150: { slidesPerView: 1, spaceBetween: 0 },
      320: { slidesPerView: 2, spaceBetween: 10 },
      768: { slidesPerView: 3, spaceBetween: 20 },
      1024: { slidesPerView: 5, spaceBetween: 30 }
    },
    updateOnWindowResize: true
  });

  swiper.update();
});
