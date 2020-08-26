/**
 * carousel-gallery.js
 * Carousel Gallery functionality.
 */

import Glide from '@glidejs/glide';


Drupal.behaviors.themekitCarouselGallery = {
  attach(context) {
    const carouselGallery = (context.classList && context.classList.contains('block--carousel-gallery')) ? context : context.querySelectorAll('.block--carousel-gallery');
    if (!carouselGallery) return;

    carouselGallery.forEach((el) => {
      new Glide(el, {
        type: 'carousel',
        startAt: 0,
        perView: 3,
        breakpoints: {
          800: {
            perView: 2,
          },
          400: {
            perView: 1,
          },
        },
      }).mount();
    });
  },
};
