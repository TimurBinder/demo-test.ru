(function (exports,main_core) {
	'use strict';

	function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { babelHelpers.defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
	var DEFAULT = {
	  containerId: 'rstuning',
	  styleSelectId: 'rstuning_styles',
	  timeoutChanageStylesDelay: 250,
	  timeoutChanageStylesId: 0
	};
	var Tuning = /*#__PURE__*/function () {
	  function Tuning() {
	    var _this = this;

	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, Tuning);
	    babelHelpers.defineProperty(this, "container", null);
	    babelHelpers.defineProperty(this, "elements", []);
	    babelHelpers.defineProperty(this, "colorMacrosContent", '');
	    babelHelpers.defineProperty(this, "colorMacrosCompiled", '');
	    babelHelpers.defineProperty(this, "macrosList", {});
	    this.options = _objectSpread(_objectSpread({}, DEFAULT), options);
	    document.addEventListener('DOMContentLoaded', this.init.bind(this));
	    main_core.Event.EventEmitter.subscribe('Redsign.Tuning.Component:afterResponse', function (_ref) {
	      var data = _ref.data;

	      if (data.MACROS_LIST) {
	        for (var macros in data.MACROS_LIST) {
	          _this.setMacros(macros, data.MACROS_LIST[macros]);
	        }

	        _this.generateCss();
	      }
	    });
	  }

	  babelHelpers.createClass(Tuning, [{
	    key: "init",
	    value: function init() {
	      this.container = document.getElementById(this.options.containerId);
	      if (!this.container) return;
	      this.elements = babelHelpers.toConsumableArray(this.container.querySelectorAll('[data-macros]'));

	      if (this.elements && this.elements.length > 0) {
	        for (var i = 0; i < this.elements.length; i++) {
	          this.setMacros(this.elements[i].getAttribute('data-macros'), this.elements[i].value);

	          if (this.elements[i].getAttribute('data-tuning-color-macros')) {
	            this.setMacros(this.elements[i].getAttribute('data-tuning-color-macros'), this.elements[i].value);
	          }
	        }
	      }
	    }
	  }, {
	    key: "generateCss",
	    value: function generateCss() {
	      this.replaceMacros();
	      this.innerStyles();
	      return true;
	    }
	  }, {
	    key: "getReadyMacros",
	    value: function getReadyMacros() {
	      var event = new CustomEvent('rs.tuning.onBeforeGetReadyMacros', {
	        'detail': {
	          'macrosList': this.macrosList
	        }
	      });
	      document.dispatchEvent(event);
	      return this.macrosList;
	    }
	  }, {
	    key: "setColorMacrosContent",
	    value: function setColorMacrosContent(content) {
	      this.colorMacrosContent = content;
	      return true;
	    }
	  }, {
	    key: "getColorMacrosContent",
	    value: function getColorMacrosContent() {
	      return this.colorMacrosContent;
	    }
	  }, {
	    key: "replaceMacros",
	    value: function replaceMacros() {
	      var content = this.getColorMacrosContent(),
	          macrosList = this.getReadyMacros();

	      for (var key1 in macrosList) {
	        content = content.replace(new RegExp('#' + key1 + '#', 'g'), macrosList[key1]);
	      }

	      this.setColorMacrosCompiled(content);
	      return true;
	    }
	  }, {
	    key: "innerStyles",
	    value: function innerStyles() {
	      var _this2 = this;

	      clearTimeout(this.options.timeoutChanageStylesId);
	      this.options.timeoutChanageStylesId = setTimeout(function () {
	        document.getElementById(_this2.options.styleSelectId).innerHTML = '' + '<style>' + _this2.getColorMacrosCompiled() + '</style>';
	      }, this.options.timeoutChanageStylesDelay);
	      return true;
	    }
	  }, {
	    key: "setColorMacrosCompiled",
	    value: function setColorMacrosCompiled(content) {
	      this.colorMacrosCompiled = content;
	      return true;
	    }
	  }, {
	    key: "getColorMacrosCompiled",
	    value: function getColorMacrosCompiled() {
	      return this.colorMacrosCompiled;
	    }
	  }, {
	    key: "setMacros",
	    value: function setMacros(macrosName, value) {
	      this.macrosList[macrosName] = value;
	      return true;
	    }
	  }]);
	  return Tuning;
	}();
	babelHelpers.defineProperty(Tuning, "instance", null);

	exports.Tuning = Tuning;

}((this.RS = this.RS || {}),BX));
//# sourceMappingURL=tuning.js.map
