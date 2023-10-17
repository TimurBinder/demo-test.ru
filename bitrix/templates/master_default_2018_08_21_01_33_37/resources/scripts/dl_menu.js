window.RS = window.RS || {};

RS.DLMenu = (function() {

  var onAnimationEvent = function (el, type, listener) {
    var events = {
      'animationend': ['webkitAnimationEnd', 'oAnimationEnd','MSAnimationEnd', 'animationend']
    };

    if (events[type]) {
      events[type].forEach(function (eventName) {
        $(el).on(eventName, listener)
      });
    }
  }

  var offAnimationEvent = function (el, type) {
    var events = {
      'animationend': ['webkitAnimationEnd', 'oAnimationEnd','MSAnimationEnd', 'animationend']
    };

    if (events[type]) {
      events[type].forEach(function (eventName) {
        $(el).off(eventName)
      });
    }
  }

  var Menu = function($el, options) {
    this.options = $.extend({}, Menu.defaultOptions, options);

    this.$menu = $el;
    this.$items = this.$menu.find(this.options.selectors.items).not(this.options.selectors.back);
    this.$back = this.$menu.find(this.options.selectors.back);

    this.initEvents();
  }

  Menu.defaultOptions = {
    animationIn: 'animate-in',
    animationOut: 'animate-out',

    selectors: {
      items: 'li',
      submenu: 'ul',
      isOpen: '.is-open',
      back: '.is-back'
    }
  }

  Menu.prototype.hasSubmenu = function($item) {
    return $item.children(this.options.selectors.submenu).length > 0;
  }

  Menu.prototype.openSubmenu = function($item) {
    var $submenu = $item.children(this.options.selectors.submenu);
    var $flyin = $submenu.clone().css('opacity', 0).insertAfter(this.$menu);

    setTimeout(function () {
      $flyin.addClass(this.options.animationIn);
      this.$menu.addClass(this.options.animationOut);

      onAnimationEvent(this.$menu, 'animationend', function () {
        offAnimationEvent(this.$menu, 'animationend');
        this.$menu.removeClass(this.options.animationOut).addClass('is-view')
        $item.addClass('is-open').closest(this.options.selectors.submenu).addClass('is-view');

        $flyin.remove();
      }.bind(this));
    }.bind(this));
  }

  Menu.prototype.back = function ($item) {
    var $submenu = $item.closest(this.options.selectors.submenu);
    var $flyin = $submenu.clone().insertAfter(this.$menu);

    setTimeout(function () {
      $flyin.addClass(this.options.animationOut );
      this.$menu.addClass(this.options.animationIn);

      onAnimationEvent(this.$menu, 'animationend', function () {
        offAnimationEvent(this.$menu, 'animationend');
        this.$menu.removeClass(this.options.animationIn);
        $flyin.remove();
      }.bind(this));

      $item.closest('.is-open').removeClass('is-open');
      $item.closest('.is-view').removeClass('is-view');
    }.bind(this));
  }

  Menu.prototype.initEvents = function() {
    var self = this;

    self.$items.on('click', function(event) {
      event.stopPropagation();

      var $item = $(this);

      if (self.hasSubmenu($item)) {
        event.preventDefault();

        self.openSubmenu($item);
      }
    });

    self.$back.on('click', function (event) {
      event.stopPropagation();
      event.preventDefault();

      self.back($(this));
    });

  }

  return Menu;

}());
