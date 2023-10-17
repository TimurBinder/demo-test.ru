RS.Application().ready(function() {
  var app = RS.Application();

  /* Sliders */
  $('[data-slider=true]').each(function(key, item) {
    var name = $(item).data('slider-name') || 'slider_' + Object.keys(app.sliders).length + 1;
    app.addSlider(name, item)
  });
  /* /Sliders */
  app.init();

  /* Social Buttons */
  $('.js-shares__btn').on('click', function() {
    $(this).closest('.js-shares').toggleClass('is-open');
  });
  /* Social Buttons */

  // Forms popups
  $(document).on('rs_forms.success', function() {
    setTimeout(function() {
      $.fancybox.close();
    }, 1000);
  });

  $(document).on('click', 'a[href^="#"]', function (event) {
    // event.preventDefault();
    //
    // var anchor = $(this).attr('href');
    // var dest = $(anchor).offset().top
    //
    // $('body').animate({
    //   scrollTop: dest
    // }, 'slow')
  });
});

(function($) {
  $.fn.setHtmlByUrl = function(options) {
    var settings = $.extend({
      'url': ''
    }, options);
    return this.each(function() {
      if ('' != settings.url) {
        var $this = $(this);
        $.ajax({
          type: 'GET',
          dataType: 'html',
          url: settings.url,
          beforeSend: function() {
            if ('localStorage' in window && window['localStorage'] !== null) {
              data = localStorage.getItem(settings.url);
              if (data) {
                localStorage.setItem(settings.url, data);
                $this.append(data);
                return false;
              }
              return true;
            }
          },
          success: function(data) {
            localStorage.setItem(settings.url, data);
            $this.append(data);
          },
        });
      }
    });
  };
})(jQuery);

(function($) {
  $.fn.rsToggleDark = function(options) {

    options = $.extend($.fn.rsToggleDark.defaults, options);

    return this.each(function() {
      var $this = $(this);

      var $back = $this.children('.overlay__back');

      if (options.progress && $back.length) {
        $status = $back.find('.load__status').html(options.message);
      } else {
        if (!$this.hasClass('overlay')) {
          $this.addClass('overlay');
          $back = $('<div class="overlay__back vcenter">' +
            '<div class="overlay__progress vcenter__in">' +
            '<div class="load">' +
            '<div class="load__ball load__1"><div class="load__inner"></div></div>' +
            '<div class="load__ball load__2"><div class="load__inner"></div></div>' +
            '<div class="load__ball load__3"><div class="load__inner"></div></div>' +
            '<div class="load__ball load__4"><div class="load__inner"></div></div>' +
            '<div class="load__ball load__5"><div class="load__inner"></div></div>' +
            '</div>' +
            '</div>' +
            '</div>');
          $back.appendTo($this);
        } else {
          $this.removeClass('overlay').children('.overlay__back').remove();
        }
      }
    });

    $.fn.rsToggleDark.defaults = {
      progress: false,
      progressLeft: false,
      progressTop: false,
      text: false,
    };
  };
})(jQuery);

/**
 * Override validator Methods
 **/
(function($, Validator) {
  if (!Validator) return;

  Validator.prototype.toggleSubmit = function() {
    if (!this.options.disable) return;

    if (this.isIncomplete() || this.hasErrors()) {
      this.$btn.addClass('disabled').attr('disabled', 'disabled')
    } else {
      this.$btn.removeClass('disabled').removeAttr('disabled');
    }
  }
})(jQuery, $.fn.validator.Constructor);
