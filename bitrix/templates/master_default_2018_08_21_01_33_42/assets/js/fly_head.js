$(document).ready(function() {
  var $flyingHead = $('.js-fly-head')
  var isFlying = false;
  var $flyStart;
  var startFlyingHead;

  $flyStart = $('[data-flyhead-start="true"]:eq(0)');
  if ($flyStart.length == 0) {
    $flyStart('.wrapper > header');
  }

  var openMenu = function() {
    var $menu = $('.js-fly-menu__items');

    $menu.removeClass('is-close');
    setTimeout(function() {
      $menu.addClass('is-open');
    }, 10);
  }

  var closeMenu = function() {
    var $menu = $('.js-fly-menu__items');

    $menu.removeClass('is-open');
    setTimeout(function() {
      $menu.addClass('is-close');

      $menu.find('.is-open').removeClass('is-open');
      $menu.find('.is-view').removeClass('is-view');
    }, 450);
  }

  var closeMenuListener = function(event) {
    var $target = $(event.target);

    if (!$target.is('.js-fly-menu__items') || $target.closest('.js-fly-menu__items').length == 0) {
      closeMenu();
      $(document).off('click', closeMenuListener);
    }
  }

  var openMenuListener = function(event) {
    event.preventDefault();

    $(document).off('click', closeMenuListener);

    if ($('.js-fly-menu__items').hasClass('is-open')) {
      closeMenu();
    } else {
      openMenu();
      setTimeout(function() {
        $(document).on('click', closeMenuListener)
      });
    }
  }

  var resizer = function() {
    startFlyingHead = $flyStart.offset().top + $flyStart.outerHeight() + 50;
  }

  var scroller = function() {
    var scrollTop = $(window).scrollTop();

    if (isFlying && scrollTop <= startFlyingHead) {
      isFlying = false;
      $flyingHead.removeClass('is-active');
      $(document).off('click', closeMenuListener);
      closeMenu();
    } else if (!isFlying && scrollTop > startFlyingHead) {
      isFlying = true;
      $flyingHead.addClass('is-active');
    }
  }

  resizer();
  scroller();
  $(window).on('resize', BX.debounce(resizer));
  $(window).on('scroll', scroller);
  $('.js-fly-menu__toggle').on('click', openMenuListener);
  new RS.DLMenu($('.b-dl-menu'));

  if (BX.Sale && BX.Sale.OrderAjaxComponent) {

    var OrderAjaxComponentDecarator = function(orderAjaxComponent) {
      this.orderAjaxComponent = orderAjaxComponent;

      if (BX.hasClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed')) {
        BX.removeClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed')
      }

      this.totalBlockScrollCheck = function() {

        if (!this.orderAjaxComponent.totalInfoBlockNode || !this.orderAjaxComponent.totalGhostBlockNode)
          return;

        var flyHeadHeight = $flyingHead.outerHeight(),
          scrollTop = BX.GetWindowScrollPos().scrollTop,
          ghostTop = BX.pos(this.orderAjaxComponent.totalGhostBlockNode).top - flyHeadHeight,
          ghostBottom = BX.pos(this.orderAjaxComponent.orderBlockNode).bottom,
          width;

        if (ghostBottom - this.orderAjaxComponent.totalBlockNode.offsetHeight < scrollTop + 20)
          BX.addClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-bottom');
        else
          BX.removeClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-bottom');

        if (scrollTop > ghostTop && !BX.hasClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed')) {
          width = this.orderAjaxComponent.totalInfoBlockNode.offsetWidth;
          BX.addClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed');
          this.orderAjaxComponent.totalGhostBlockNode.style.paddingTop = this.orderAjaxComponent.totalInfoBlockNode.offsetHeight + 'px';
          this.orderAjaxComponent.totalInfoBlockNode.style.width = width + 'px';
          this.orderAjaxComponent.totalInfoBlockNode.style.marginTop = flyHeadHeight + 'px';
        } else if (scrollTop < ghostTop && BX.hasClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed')) {
          BX.removeClass(this.orderAjaxComponent.totalInfoBlockNode, 'bx-soa-cart-total-fixed');
          this.orderAjaxComponent.totalGhostBlockNode.style.paddingTop = 0;
          this.orderAjaxComponent.totalInfoBlockNode.style.width = '';
          this.orderAjaxComponent.totalInfoBlockNode.style.marginTop = '0px';
        }
      }
    }

    var orderAjaxComponentDecorated = new OrderAjaxComponentDecarator(BX.Sale.OrderAjaxComponent);

    BX.unbind(window, 'scroll', BX.proxy(BX.Sale.OrderAjaxComponent.totalBlockScrollCheck, BX.Sale.OrderAjaxComponent));
    BX.bind(window, 'scroll',  BX.proxy(orderAjaxComponentDecorated.totalBlockScrollCheck, orderAjaxComponentDecorated));
    BX.proxy(orderAjaxComponentDecorated.totalBlockScrollCheck, orderAjaxComponentDecorated)();
}
});
