'use strict';

function RSFlyingCart(params) {
  this.id = params['ID'];
  this.$cart = $("#" + this.id);
  this.$content = this.$cart.find('.flying-cart__content');
  this.$btn = this.$cart.find('.flying-cart__icon');

  this.templateName = params['TEMPLATE_NAME'];
  this.arParams = params['TEMPLATE_PARAMS'];
  this.ajaxPath = params['AJAX_PATH'];
  this.siteId = params['SITE_ID'];

  this.resize();

  BX.addCustomEvent('OnBasketChange', $.proxy(this.refresh, this));
  this.$btn.on('click', $.proxy(this.openPopup, this));
  $(window).on('resize.flycart', BX.debounce($.proxy(this.resize, this), 250));

  this.initContentEvents();
}

RSFlyingCart.prototype.initContentEvents = function() {
  this.$content.find('.product-item-amount .dropdown-menu > li').on('click', this.changeQuantity);
  this.$content.find('.product-item-amount-field').on('change', $.proxy(this.updateQuantity, this));
  this.$content.find('.dropdown').on('show.bs.dropdown', $.proxy(this.quantityDropdownPosition, this));

  this.$content.find('.js-cart__remove').on('click', $.proxy(function (e) {
    e.preventDefault();
    this.startLoader();
    var id = $(e.target).closest('[data-id]').data('id');
    var productId = $(e.target).closest('[data-product-id]').data('product-id');

    Basket.delete(id, productId);
  }, this));

  this.$content.find('.js-cart__clear').on('click', $.proxy(function() {
    this.startLoader();

    var ids = $.makeArray(this.$cart.find('tr[data-id]').map(function () {
    	return $(this).data('id')
    }));

    Basket.clear(ids);
  }, this));
}

RSFlyingCart.prototype.refresh = function(data, type) {
  this.startLoader();

  if (type == 'ADD2CART') {
    this.add2cart();
  }

  BX.ajax({
    url: this.ajaxPath,
    method: 'POST',
    dataType: 'json',
    data: {
      action: 'get',
      sessid: BX.bitrix_sessid(),
      templateName: this.templateName,
      arParams: this.arParams,
      siteId: this.siteId
    },
    onsuccess: function(result) {
      this.$content.html(result['CONTENT']);
      this.updateCount(result['COUNT']);
      this.hideOrShow();

      if (this.isShown()) {
        this.$cart
          .css('transition', '0s')
          .css('height', this.$content.outerHeight());

        setTimeout($.proxy(function () {
          this.$cart.css('transition', '');
        }, this), 0);
      }

      this.initContentEvents();
      this.endLoader();
    }.bind(this)
  });
}

RSFlyingCart.prototype.hideOrShow = function() {
  if (RS.Application().inBreakpoint('xs') && Basket && Basket.inbasket().length > 0) {
    this.$cart.show();
  } else if (!this.isShown()) {
    this.$cart.hide();
  }
}

RSFlyingCart.prototype.resize = function() {
  this.hideOrShow();
  this.position();
}

RSFlyingCart.prototype.position = function() {
  var cartButtonWidth = 95;
  var container = $('<div>').addClass('container').css('visibility', 'hidden');
  $('body').append(container);
  var containerWidth = container.outerWidth();
  var containerOffsetLeft = container.offset().left + containerWidth;
  var windowWidth = $(window).outerWidth();

  this.$cart.css('right', (windowWidth > containerOffsetLeft + cartButtonWidth) ? windowWidth - containerOffsetLeft - cartButtonWidth : 15);
  container.remove();
}

RSFlyingCart.prototype.openPopup = function() {

  if (!RS.Application().inBreakpoint('sm')) {
    window.location.href = this.arParams['PATH_TO_CART'];
    return;
  }

  var that = this;

  this.$cart
    .css('height', this.$content.outerHeight())
    .css('top', this.$cart.offset().top)
    .css('position', 'absolute')
    .addClass('is-open')

  $('body').append('<div class="fancybox-container fancybox-is-open"><div class="fancybox-bg"></div></div>');
  this.$cart.trigger('open.rs.flying_cart');

  var close = function (event) {
    event.preventDefault();
    that.closePopup();
    $('.fancybox-container').off('click.close-cart');
    that.$cart.off('click.close-cart');
  }

  $('.fancybox-container').on('click.close-cart', close);
  this.$cart.on('click.close-cart', '.js-cart-close', close);
  $(document).on('keyup.cart_escape', $.proxy(function (e) {
    if (e.keyCode === 27) {
      close(e);
    }
  }, this));
}

RSFlyingCart.prototype.closePopup = function() {
  this.$cart
    .css('height', '')
    .css('top', '')
    .css('position', '')
    .removeClass('is-open');

  $('.fancybox-container').remove();
  $(this.$cart).trigger('close.rs.flying_cart');
  $(document).off('keyup.cart_escape');
}

RSFlyingCart.prototype.changeQuantity = function() {
  var $el = $(this);
  if ($el.hasClass('product-item-amount-var')) {
    $el.closest('.product-item-amount').find('.product-item-amount-field').val($el.text()).change();
  } else {
    $el.closest('.product-item-amount').find('.product-item-amount-field').val('').focus();
  }
}

RSFlyingCart.prototype.updateQuantity = function(e) {
  this.startLoader();

  var $input = $(e.target);
  var quantity = parseFloat($input.val(), 10);
  var itemId = $input.closest('[data-id]').data('id');
  var defer = $.Deferred();

  var request = Basket.updateQuantity(itemId, quantity);
}

RSFlyingCart.prototype.updateCount = function(count) {
  this.$btn.find('.flying-cart__count').html(count);
  $('.js-mobile-cart-icon').html(count);
  $('.js-cart-count').html(count);
}

RSFlyingCart.prototype.quantityDropdownPosition = function(e) {
  var scrollTop = this.$content.find('.js-cart__products').scrollTop();
  var $target = $(e.target);
  $target.find('.dropdown-menu').css('margin-top', -scrollTop + 2);

  this.$content.find('.js-cart__products').one('scroll.dropdown', function() {
    $target.removeClass('open');
  });
}

RSFlyingCart.prototype.isShown = function() {
  return this.$cart.hasClass('is-open');
}

RSFlyingCart.prototype.startLoader = function () {
  this.$cart.addClass('is-loading');
}
RSFlyingCart.prototype.endLoader = function () {
  this.$cart.removeClass('is-loading');
}

RSFlyingCart.prototype.add2cart = function (e) {
  this.$cart.addClass('in-cart-animation');
  setTimeout($.proxy(function () {
      this.$cart.removeClass('in-cart-animation');
  }, this), 500);
}
