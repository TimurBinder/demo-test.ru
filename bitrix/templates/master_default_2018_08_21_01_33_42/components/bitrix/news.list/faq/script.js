RS.Application().ready(function () {
  var $faq = $('.js-faq');

  if (window.location.hash) {
    $faq.find('.collapsed[href="#collapse_' + window.location.hash.split('#')[1] + '"]').click();
  }

  $('#faq_accordion').on('shown.bs.collapse', function () {
      var $active = $faq.find('.collapse.in').closest('.panel');
      window.location.hash = $active.data('code');
  });

  $faq.find('[data-filter]').on('click', function (e) {
    e.preventDefault();

    var filter = $(this).data('filter');

    if (filter) {
      $faq
        .find('.panel')
        .show()
        .not('[data-type="' + filter  + '"]')
        .hide()

      $faq.find('.panel:visible:eq(0) a.collapsed').click();
    } else {
      $faq.find('.panel').show();
    }

    $(this)
    .removeClass('btn-default')
      .addClass('btn-primary')
      .siblings('.btn-primary')
        .removeClass('btn-primary')
        .addClass('btn-default')
  });
});
