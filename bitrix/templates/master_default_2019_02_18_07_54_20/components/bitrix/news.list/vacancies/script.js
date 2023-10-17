RS.Application().ready(function () {
  var $vacancies = $('.js-vacancies');

  if (window.location.hash) {
    $vacancies.find('.collapsed[href="#collapse_' + window.location.hash.split('#')[1] + '"]').click();
  }

  $('#vacancies_accordion').on('shown.bs.collapse', function () {
      var $active = $vacancies.find('.collapse.in').closest('.panel');
      window.location.hash = $active.data('code');
  });

  $vacancies.find('[data-filter]').on('click', function (e) {
    e.preventDefault();

    var filter = $(this).data('filter');

    if (filter) {
      $vacancies
        .find('.panel')
        .show()
        .not('[data-type="' + filter  + '"]')
        .hide()

      $vacancies.find('.panel:visible:eq(0) a.collapsed').click();
    } else {
      $vacancies.find('.panel').show();
    }

    $(this)
    .removeClass('btn-default')
      .addClass('btn-primary')
      .siblings('.btn-primary')
        .removeClass('btn-primary')
        .addClass('btn-default')
  });
});
