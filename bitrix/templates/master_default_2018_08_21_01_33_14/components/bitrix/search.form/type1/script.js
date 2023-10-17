RS.Application().ready(function () {

  $('.js-head-search__open').on('click', function (e) {
    e.preventDefault();

    var $search = $(this).closest('.js-head-search');

    $search.find('.js-head-search__form').show();

    $search.closest('.js-head-search').addClass('is-open');
    $search.find('input:eq(0)').focus();
  });

  $('.js-head-search__close').on('click', function (e) {
    e.preventDefault();
    
    var $search = $(this).closest('.js-head-search');
    $(this).closest('.js-head-search').removeClass('is-open');

    setTimeout(function () {
      $search.find('.js-head-search__form').hide();
    }, 350)
  });
});
