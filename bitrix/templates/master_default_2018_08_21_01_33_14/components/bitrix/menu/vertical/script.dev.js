RS.computeMainMenu = function() {
  var $menu, $nav, $items;

  if ($('.is-page-home').length > 0) {
    $menu = $('.js-menu');
    $nav = $menu.find('.js-menu__nav');
    $items = $nav.children('li');

    if ($items.length > 9) {
      $menu.addClass('is-more-items');
    }
  }
}

RS.Application().ready(function() {
  //RS.computeMainMenu();

  /* Main menu hover */
  var $menu = $('.js-menu');

  var activate = function(row) {
    if (RS.Application().inBreakpoint('sm')) {
      var $row = $(row),
        $subrow;

      if ($row.hasClass('dropdown')) {
        $subrow = $row.find('.dropdown-menu:eq(0)');
        $row.css('visibility', 'hidden').addClass('open');

        if (RS.Application().getWidth() > $subrow.offset().left + $subrow.outerWidth()) {
          $row.css('visibility', 'visible');
        } else {
          $row.removeClass('open').css('visibility', 'visible');
        }
      } else {
        $row.addClass('open');
      }
    }
  }

  var deactivate = function(row) {
    if (RS.Application().inBreakpoint('sm')) {
      var $row = $(row);

      //if ($row.hasClass('open')) {
      $row.removeClass('open');
      $row.find('.open').removeClass('open');
      //}
    }
  }

  if ($menu.data('type') == 'AIM') {
    $menu.find('.dropdown-menu, .js-menu__nav').each(function() {
      $(this).menuAim({
        activate: activate,
        deactivate: deactivate,
        exitMenu: function(menu) {
          return true;
        }
      });
    });
  } else {
    $menu.find('a').on('mouseover', function() {
      var row = this.parentNode;

      activate(this.parentNode);

      $(row).one('mouseleave', function() {
        deactivate(row);
      });
    }).on('click', function(event) {
      if (RS.Application().inBreakpoint('sm')) {
        event.stopPropagation();
      }
    });
  }

  // $menu.find('.js-menu__nav > .dropdown > a,.js-menu__more-btn').on('mouseover', function() {
  //   var row = this.parentNode;
  //
  //   activate(this.parentNode);
  //
  //   $(row).one('mouseleave', function() {
  //     deactivate(row);
  //   });
  // }).on('click', function(event) {
  //   if (RS.Application().inBreakpoint('sm')) {
  //     event.stopPropagation();
  //   }
  // });

  $menu.find('.js-menu__plus').on('click', function(event) {
    event.preventDefault();
    $(this).closest('.dropdown').toggleClass('open');
  });

  $('.b-vertical-menu .navbar-toggle').on('click', function(event) {
    if (RS.Application().inBreakpoint('sm')) {
      event.stopPropagation();
    }
  });

  $menu.find('a').on('click', function(event) {
    event.stopPropagation();
  });
});
