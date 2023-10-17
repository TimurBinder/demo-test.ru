RS.Application().ready(function () {
  BX.addCustomEvent('change.rs_favorite', function () {
    if (RS.Favorite) {
      $('.js-favorite-count').html(RS.Favorite.products.length);
    }
  });
});