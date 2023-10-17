$(document).ready(function () {
    var $allPictures = $('.js-light-gallery__allpictures');

    $allPictures.children('a').fancybox();

    $('.js-light-gallery__item').on('click', function (e) {
      e.preventDefault();

      var $item = $(this);

      if($item.data('code')) {
        $('.js-light-gallery__' + $item.data('code') + ':eq(0)').click();
      }
    });
});
