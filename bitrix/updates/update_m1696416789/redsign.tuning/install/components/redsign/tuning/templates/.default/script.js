(function (exports,main_core) {
	'use strict';

	function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { babelHelpers.defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
	var DEFAULT = {
	  containerId: 'rstuning',
	  animationDelay: 310,
	  sidebarWidth: 415,
	  contentWidth: 650,
	  selectors: {
	    sidebar: '.js-rstuning__sidebar',
	    content: '.js-rstuning__content',
	    preloader: '.js-rstuning__preloader',
	    openButton: '.js-rstuning__open-button',
	    closeButton: '.js-rstuning__close-button',
	    mainOverlay: '.js-rstuning__main-overlay',
	    contentOverlay: '.js-rstuning__content-overlay',
	    toggleSidebar: '.js-rstuning__toggle-sidebar',
	    defaultSettings: '.js-tuning-default-settings'
	  },
	  breakpoint: {
	    top: 415 + 650,
	    low: 320
	  }
	};
	var TuningComponent = /*#__PURE__*/function (_Event$EventEmitter) {
	  babelHelpers.inherits(TuningComponent, _Event$EventEmitter);

	  function TuningComponent() {
	    var _this;

	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, TuningComponent);
	    _this = babelHelpers.possibleConstructorReturn(this, babelHelpers.getPrototypeOf(TuningComponent).call(this));
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "container", null);
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "modTabs", false);
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "keyCode", {
	      ESC: 27
	    });
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "swipeData", {
	      touchDown: false,
	      originalPosition: null,
	      el: 2,
	      result: {}
	    });
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "request", {
	      delay: 800,
	      timeoutId: 0
	    });
	    babelHelpers.defineProperty(babelHelpers.assertThisInitialized(_this), "isMobile", {
	      Android: function Android() {
	        return navigator.userAgent.match(/Android/i);
	      },
	      BlackBerry: function BlackBerry() {
	        return navigator.userAgent.match(/BlackBerry/i);
	      },
	      iOS: function iOS() {
	        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	      },
	      Opera: function Opera() {
	        return navigator.userAgent.match(/Opera Mini/i);
	      },
	      Windows: function Windows() {
	        return navigator.userAgent.match(/IEMobile/i);
	      },
	      any: function any() {
	        return isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows();
	      }
	    });
	    _this.options = _objectSpread(_objectSpread({}, DEFAULT), options);
	    document.addEventListener('DOMContentLoaded', _this.init.bind(babelHelpers.assertThisInitialized(_this)));
	    window.addEventListener('resize', _this.windowResize.bind(babelHelpers.assertThisInitialized(_this)));
	    return _this;
	  }

	  babelHelpers.createClass(TuningComponent, [{
	    key: "init",
	    value: function init() {
	      this.container = document.getElementById(this.options.containerId);
	      if (!this.container) return;
	      var elements = [],
	          i = 0;
	      this.modTabs = document.querySelector('.js-rstuning.mod-tabs') ? true : false;
	      this.container.classList.add('rstuning__loaded');
	      this.setWidth();

	      if (this.container.classList.contains('open')) {
	        document.querySelector('html').classList.toggle('rstuning-enabled');
	      } // main - close\open


	      this.container.querySelector(this.options.selectors.openButton).addEventListener('click', this.closeopen.bind(this));
	      this.container.querySelector(this.options.selectors.closeButton).addEventListener('click', this.closeopen.bind(this));
	      document.querySelector(this.options.selectors.mainOverlay).addEventListener('click', this.closeopen.bind(this)); // main - close\open by swipe

	      if (!this.isMobile.iOS()) {
	        this.container.addEventListener('touchstart', this.touchStart.bind(this));
	        this.container.addEventListener('touchend', this.touchEnd.bind(this));
	        this.container.addEventListener('touchmove', this.touchMove.bind(this));
	      } // open sidebar


	      if (this.modTabs) {
	        elements = this.container.querySelectorAll(this.options.selectors.toggleSidebar);

	        if (elements && elements.length > 0) {
	          for (i = 0; i < elements.length; i++) {
	            elements[i].addEventListener('click', this.toggleSidebar.bind(this));
	          }
	        }

	        this.container.querySelector(this.options.selectors.contentOverlay).addEventListener('click', this.toggleSidebar.bind(this));
	      } // default settings


	      elements = this.container.querySelectorAll(this.options.selectors.defaultSettings);

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('click', this.restoreDefaultSettings.bind(this));
	        }
	      } // save macros


	      elements = this.container.querySelectorAll('[data-macros]');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('change', this.changeMacrosFields.bind(this));
	        }
	      } // change reload status


	      elements = this.container.querySelectorAll('input');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('change', this.changeField.bind(this));
	        }
	      }

	      elements = this.container.querySelectorAll('select');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('change', this.changeField.bind(this));
	        }
	      }

	      elements = this.container.querySelectorAll('textarea');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('change', this.changeField.bind(this));
	        }
	      } // tabs


	      elements = this.container.querySelectorAll('.js-rstuning-nav');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('click', this.switchTab.bind(this));
	        }
	      } // form submit


	      if (this.container.querySelector('form')) {
	        this.container.querySelector('form').addEventListener('submit', this.formSubmit.bind(this));
	      } else {
	        console.warn('No form detected! Cant save settings.');
	      } // scroll


	      elements = this.container.querySelectorAll('.rstuning__scroll');

	      if (elements && elements.length > 0) {
	        for (i = 0; i < elements.length; i++) {
	          elements[i].addEventListener('wheel', function (event) {
	            event.stopPropagation();
	          });
	        }
	      }

	      return true;
	    }
	  }, {
	    key: "windowResize",
	    value: function windowResize() {
	      this.setWidth();
	      return true;
	    }
	  }, {
	    key: "closeopen",
	    value: function closeopen(e) {
	      var _this2 = this;

	      var goOpen = this.container.classList.contains('open') ? false : true; // main

	      document.querySelector('html').classList.toggle('rstuning-enabled');

	      if (goOpen) {
	        BX.setCookie('RSTUNING_COOKIE_OPEN', 'Y');
	        this.container.classList.remove('closed');
	        setTimeout(function () {
	          _this2.container.classList.add('open');

	          window.addEventListener('keyup', _this2.windowKeyUp.bind(_this2));
	        }, 10);
	      } else {
	        BX.setCookie('RSTUNING_COOKIE_OPEN', 'N');
	        this.container.classList.remove('open');
	        this.container.classList.remove('loading');
	        this.container.classList.remove('open-sidebar');
	        setTimeout(function () {
	          _this2.container.classList.add('closed');

	          window.removeEventListener('keyup', _this2.windowKeyUp.bind(_this2));
	        }, this.options.animationDelay);
	      } // sidebar


	      if (this.modTabs) {
	        this.container.classList.remove('open-sidebar');
	        this.container.querySelector(this.options.selectors.contentOverlay).classList.remove('open');
	      }

	      this.setWidth();
	      if (!!e) e.preventDefault();
	      return true;
	    }
	  }, {
	    key: "windowKeyUp",
	    value: function windowKeyUp(e) {
	      if (e.keyCode == this.keyCode.ESC) {
	        this.closeopen();
	      }
	    }
	  }, {
	    key: "toggleSidebar",
	    value: function toggleSidebar() {
	      if (!this.modTabs) return false;
	      this.container.classList.toggle('open-sidebar');
	      this.container.querySelector(this.options.selectors.contentOverlay).classList.toggle('open');
	      return true;
	    }
	  }, {
	    key: "changeMacrosFields",
	    value: function changeMacrosFields(e) {
	      var _window = window,
	          rsTuning = _window.rsTuning;
	      rsTuning.setMacros(e.target.getAttribute('data-macros'), e.target.value);

	      if (e.target.getAttribute('data-tuning-color-macros')) {
	        rsTuning.setMacros(e.target.getAttribute('data-tuning-color-macros'), e.target.value);
	      }

	      rsTuning.generateCss();
	      return true;
	    }
	  }, {
	    key: "changeField",
	    value: function changeField(e) {
	      var el = e.target;

	      while ((el = el.parentElement) && !el.classList.contains('js-rs_option_info')) {
	      }

	      if (el && el.getAttribute('data-reload') == 'Y') {
	        this.container.setAttribute('data-reload', 'Y');
	      }

	      this.formSubmit();
	      return true;
	    }
	  }, {
	    key: "switchTab",
	    value: function switchTab(e) {
	      var link = e.currentTarget,
	          tabId = link.getAttribute('data-tabid'),
	          tab = this.container.querySelector('.js-rstuning__tab-content [data-tabid="' + tabId + '"]'),
	          el = false,
	          elements = [],
	          i = 0;
	      if (!tab || !tabId) return;
	      BX.onCustomEvent(window, 'rs.tuning.tabs.before.change', [tab]);
	      if (this.container.querySelector('.js-content-title') > 0) this.container.querySelector('.js-content-title').innerHTML = link.getAttribute('data-name');
	      el = link;

	      while ((el = el.parentElement) && !el.classList.contains('js-rstuning__tab-switcher')) {
	      }

	      if (el) {
	        elements = el.querySelectorAll('a');

	        if (elements && elements.length > 0) {
	          for (i = 0; i < elements.length; i++) {
	            elements[i].classList.remove('active');
	          }
	        }
	      }

	      link.classList.add('active');
	      el = tab;

	      while ((el = el.parentElement) && !el.classList.contains('js-rstuning__tab-content')) {
	      }

	      if (el) {
	        elements = el.querySelectorAll('.js-rstuning__tab-pane');

	        if (elements && elements.length > 0) {
	          for (i = 0; i < elements.length; i++) {
	            elements[i].classList.remove('active');
	          }
	        }
	      }

	      tab.classList.add('active');
	      BX.setCookie('RSTUNING_COOKIE_TAB_ACTIVE', tabId);
	      this.container.classList.remove('open-sidebar');
	      document.querySelector(this.options.selectors.contentOverlay).classList.remove('open');
	      BX.onCustomEvent(window, 'rs.tuning.tabs.after.change', [tab]);
	      return true;
	    }
	  }, {
	    key: "formSubmit",
	    value: function formSubmit() {
	      var _this3 = this;

	      var form = this.container.querySelector('form'),
	          xhr = new XMLHttpRequest(),
	          data = new FormData(form);
	      clearTimeout(this.request.timeoutId);
	      this.request.timeoutId = setTimeout(function () {
	        _this3.loading();

	        xhr.open('POST', form.getAttribute('action'), false);
	        xhr.addEventListener('load', _this3.requestCallback.bind(_this3));
	        xhr.send(data);
	      }, this.request.delay);
	      return true;
	    }
	  }, {
	    key: "requestCallback",
	    value: function requestCallback(e) {
	      var _this4 = this;

	      if (e.currentTarget.status != 200) {
	        console.error(e.currentTarget.status + ': ' + e.currentTarget.statusText);
	      } else {
	        if (this.container.getAttribute('data-reload') == 'Y') {
	          window.location.reload();
	        } else {
	          var response = JSON.parse(e.currentTarget.response);
	          this.emit('Redsign.Tuning.Component:afterResponse', response);
	          setTimeout(function () {
	            _this4.loading();
	          }, 1500);
	        }
	      }

	      return true;
	    }
	  }, {
	    key: "restoreDefaultSettings",
	    value: function restoreDefaultSettings(e) {
	      var form = this.container.querySelector('form'),
	          xhr = new XMLHttpRequest(),
	          postQuery = "site_id=" + this.container.getAttribute('data-siteid') + "&rstuning_ajax=Y&rstuning_action=restoredefaultsettings";
	      this.container.setAttribute('data-reload', 'Y');
	      this.loading();
	      xhr.open('POST', form.getAttribute('action'), true);
	      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
	      xhr.addEventListener('load', this.requestCallback.bind(this));
	      xhr.send(postQuery);
	      return true;
	    }
	  }, {
	    key: "swipe",
	    value: function swipe(e) {
	      var x = 0,
	          y = 0,
	          dx,
	          dy;

	      if (e && e.touches[0]) {
	        x = e.touches[0].pageX;
	        y = e.touches[0].pageY;
	      } else if (e && e.originalEvent && e.originalEvent.touches[0]) {
	        x = e.originalEvent.touches[0].pageX;
	        y = e.originalEvent.touches[0].pageY;
	      } else if (e && e.changedTouches[0]) {
	        x = e.changedTouches[0].pageX;
	        y = e.changedTouches[0].pageY;
	      } else {
	        console.warn('TuningComponent: swipe - no coordinates');
	      }

	      if (Math.abs(this.swipeData.originalPosition.x - x) > 80) {
	        dx = x > this.swipeData.originalPosition.x ? 'right' : 'left';
	      } else {
	        dx = null;
	      }

	      if (Math.abs(this.swipeData.originalPosition.y - y) > 80) {
	        dy = y > this.swipeData.originalPosition.y ? 'down' : 'up';
	      } else {
	        dy = null;
	      }

	      this.swipeData.result = {
	        direction: {
	          x: dx,
	          y: dy
	        },
	        offset: {
	          x: x - this.swipeData.originalPosition.x,
	          y: this.swipeData.originalPosition.y - y
	        }
	      };
	      return true;
	    }
	  }, {
	    key: "touchStart",
	    value: function touchStart(e) {
	      this.swipeData.touchDown = true;

	      if (e && e.touches[0]) {
	        this.swipeData.originalPosition = {
	          x: e.touches[0].pageX,
	          y: e.touches[0].pageY
	        };
	      } else if (e && e.originalEvent && e.originalEvent.touches[0]) {
	        this.swipeData.originalPosition = {
	          x: e.originalEvent.touches[0].pageX,
	          y: e.originalEvent.touches[0].pageY
	        };
	      } else if (e && e.changedTouches[0]) {
	        this.swipeData.originalPosition = {
	          x: e.changedTouches[0].pageX,
	          y: e.changedTouches[0].pageY
	        };
	      } else {
	        console.error('TuningComponent: touch coordinates get error.');
	      }

	      return true;
	    }
	  }, {
	    key: "touchEnd",
	    value: function touchEnd(e) {
	      this.swipe(e);
	      this.swipeData.touchDown = false;
	      this.swipeData.originalPosition = null;

	      if (this.swipeData.result.direction.x == 'right' && this.container.classList.contains('open')) {
	        this.toggleSidebar();
	      } else if (this.swipeData.result.direction.x == 'left' && this.container.classList.contains('open-sidebar')) {
	        this.toggleSidebar();
	      } else if (this.swipeData.result.direction.x == 'left' && !this.container.classList.contains('open-sidebar')) {
	        this.closeopen();
	      }

	      return true;
	    }
	  }, {
	    key: "touchMove",
	    value: function touchMove(e) {
	      if (!this.swipeData.touchDown) {
	        return;
	      }
	    }
	  }, {
	    key: "setWidth",
	    value: function setWidth() {
	      if (window.innerWidth <= this.options.breakpoint.top) {
	        this.container.querySelector(this.options.selectors.preloader).style.width = Math.min(this.options.sidebarWidth + this.options.contentWidth, window.innerWidth) + 'px';
	        this.container.querySelector(this.options.selectors.sidebar).style.width = Math.min(this.options.sidebarWidth, window.innerWidth) + 'px';
	        this.container.querySelector(this.options.selectors.content).style.width = Math.min(this.options.contentWidth, window.innerWidth) + 'px';
	      } else {
	        this.container.querySelector(this.options.selectors.sidebar).style.width = '';
	        this.container.querySelector(this.options.selectors.content).style.width = '';
	      } // sidebar


	      if (this.modTabs) {
	        this.container.classList.remove('open-sidebar');
	        this.container.querySelector(this.options.selectors.contentOverlay).classList.remove('open');
	      }

	      return true;
	    }
	  }, {
	    key: "loading",
	    value: function loading() {
	      if (this.container.getAttribute('data-reload') == 'Y') {
	        this.container.classList.toggle('loading');
	      }

	      return true;
	    }
	  }, {
	    key: "getTuningComponent",
	    value: function getTuningComponent() {
	      return this.container;
	    }
	  }]);
	  return TuningComponent;
	}(main_core.Event.EventEmitter);
	babelHelpers.defineProperty(TuningComponent, "instance", null);

	exports.TuningComponent = TuningComponent;

}((this.RS = this.RS || {}),BX));
//# sourceMappingURL=script.js.map
