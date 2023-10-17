(function (exports) {
	'use strict';

	var containers = []; // will store container HTMLElement references

	var styleElements = []; // will store {prepend: HTMLElement, append: HTMLElement}

	var usage = 'insert-css: You need to provide a CSS string. Usage: insertCss(cssString[, options]).';

	function insertCss(css, options) {
	  options = options || {};

	  if (css === undefined) {
	    throw new Error(usage);
	  }

	  var position = options.prepend === true ? 'prepend' : 'append';
	  var container = options.container !== undefined ? options.container : document.querySelector('head');
	  var containerId = containers.indexOf(container); // first time we see this container, create the necessary entries

	  if (containerId === -1) {
	    containerId = containers.push(container) - 1;
	    styleElements[containerId] = {};
	  } // try to get the correponding container + position styleElement, create it otherwise


	  var styleElement;

	  if (styleElements[containerId] !== undefined && styleElements[containerId][position] !== undefined) {
	    styleElement = styleElements[containerId][position];
	  } else {
	    styleElement = styleElements[containerId][position] = createStyleElement();

	    if (position === 'prepend') {
	      container.insertBefore(styleElement, container.childNodes[0]);
	    } else {
	      container.appendChild(styleElement);
	    }
	  } // strip potential UTF-8 BOM if css was read from a file


	  if (css.charCodeAt(0) === 0xFEFF) {
	    css = css.substr(1, css.length);
	  } // actually add the stylesheet


	  if (styleElement.styleSheet) {
	    styleElement.styleSheet.cssText += css;
	  } else {
	    styleElement.textContent += css;
	  }

	  return styleElement;
	}

	function createStyleElement() {
	  var styleElement = document.createElement('style');
	  styleElement.setAttribute('type', 'text/css');
	  return styleElement;
	}

	var insertCss_1 = insertCss;
	var insertCss_2 = insertCss;
	insertCss_1.insertCss = insertCss_2;

	function e(t, e) {
	  for (var i = 0; i < e.length; i++) {
	    var o = e[i];
	    o.enumerable = o.enumerable || !1, o.configurable = !0, "value" in o && (o.writable = !0), Object.defineProperty(t, o.key, o);
	  }
	}

	function i(t, i, o) {
	  return i && e(t.prototype, i), o && e(t, o), t;
	}

	function o(t) {
	  return "number" == typeof t && !isNaN(t);
	}

	function s(t, e, i) {
	  return Math.min(Math.max(t, e), i);
	}

	function n(t) {
	  if (0 === t.type.indexOf("touch")) {
	    var e = t.touches[0];
	    return {
	      x: e.clientX,
	      y: e.clientY
	    };
	  }

	  return {
	    x: t.clientX,
	    y: t.clientY
	  };
	}

	function r(t) {
	  return 1 == t.length ? "0" + t : "" + t;
	}

	var h = function () {
	  function t(t) {
	    this._rgba = {
	      r: 0,
	      g: 0,
	      b: 0,
	      a: 1
	    }, this._hsva = {
	      h: 0,
	      s: 0,
	      v: 0,
	      a: 1
	    }, this.fromHex(t);
	  }

	  var e = t.prototype;
	  return e.fromHex = function (t) {
	    t || (t = 0), o(t) ? (this._hexNumber = t, this._hexString = function (t) {
	      return "#" + ("00000" + (0 | t).toString(16)).substr(-6).toUpperCase();
	    }(this._hexNumber)) : (this._hexString = t.toUpperCase(), this._hexNumber = u(this._hexString));

	    var e = function (t) {
	      return {
	        r: (t >> 16 & 255) / 255,
	        g: (t >> 8 & 255) / 255,
	        b: (255 & t) / 255
	      };
	    }(this._hexNumber),
	        i = e.g,
	        s = e.b;

	    this._rgba.r = e.r, this._rgba.g = i, this._rgba.b = s;

	    var n = function (t) {
	      var e,
	          i = t.r,
	          o = t.g,
	          s = t.b,
	          n = Math.max(i, o, s),
	          r = Math.min(i, o, s),
	          h = n - r,
	          u = 0 === n ? 0 : h / n,
	          a = n;
	      if (n == r) e = 0;else {
	        switch (n) {
	          case i:
	            e = (o - s) / h + (o < s ? 6 : 0);
	            break;

	          case o:
	            e = (s - i) / h + 2;
	            break;

	          case s:
	            e = (i - o) / h + 4;
	        }

	        e /= 6;
	      }
	      return {
	        h: e,
	        s: u,
	        v: a
	      };
	    }(this._rgba),
	        r = n.s,
	        h = n.v;

	    this._hsva.h = n.h, this._hsva.s = r, this._hsva.v = h, this._updateBrightness();
	  }, e.fromHsv = function (t) {
	    var e = t.s,
	        i = t.v;
	    this._hsva.h = t.h, this._hsva.s = e, this._hsva.v = i;

	    var o = function (t) {
	      var e = t.h,
	          i = t.s,
	          o = t.v;
	      e *= 6;
	      var s = Math.floor(e),
	          n = e - s,
	          r = o * (1 - i),
	          h = o * (1 - n * i),
	          u = o * (1 - (1 - n) * i),
	          a = s % 6;
	      return {
	        r: [o, h, r, r, u, o][a],
	        g: [u, o, o, h, r, r][a],
	        b: [r, r, u, o, o, h][a]
	      };
	    }(this._hsva),
	        s = o.g,
	        n = o.b;

	    this._rgba.r = o.r, this._rgba.g = s, this._rgba.b = n, this._hexString = function (t) {
	      var e = t.g,
	          i = t.b;
	      return ["#", r(Math.round(255 * t.r).toString(16)), r(Math.round(255 * e).toString(16)), r(Math.round(255 * i).toString(16))].join("").toUpperCase();
	    }(this._rgba), this._hexNumber = u(this._hexString), this._updateBrightness();
	  }, e._updateBrightness = function () {
	    var t = this._rgba;
	    this._brightness = (299 * t.r + 587 * t.g + 114 * t.b) / 1e3, this._isDark = this._brightness < .5, this._isLight = !this._isDark;
	  }, i(t, [{
	    key: "rgb",
	    get: function get() {
	      return this._rgba;
	    }
	  }, {
	    key: "hsv",
	    get: function get() {
	      return this._hsva;
	    }
	  }, {
	    key: "hex",
	    get: function get() {
	      return this._hexNumber;
	    }
	  }, {
	    key: "hexString",
	    get: function get() {
	      return this._hexString;
	    }
	  }, {
	    key: "brightness",
	    get: function get() {
	      return this._brightness;
	    }
	  }, {
	    key: "isDark",
	    get: function get() {
	      return this._isDark;
	    }
	  }, {
	    key: "isLight",
	    get: function get() {
	      return this._isLight;
	    }
	  }]), t;
	}();

	function u(t) {
	  return parseInt(t.replace("#", ""), 16);
	}

	var a = function () {
	  function t(t) {
	    var e = this;
	    void 0 === t && (t = {}), this._widthUnits = "px", this._heightUnits = "px", this._huePosition = 0, this._hueHeight = 0, this._maxHue = 0, this._inputIsNumber = !1, this._saturationWidth = 0, this._isChoosing = !1, this._callbacks = [], this.width = 0, this.height = 0, this.hue = 0, this.position = {
	      x: 0,
	      y: 0
	    }, this.color = new h(0), this.backgroundColor = new h(0), this.hueColor = new h(0), this._onSaturationMouseDown = function (t) {
	      var i = e.$saturation.getBoundingClientRect(),
	          o = n(t),
	          s = o.x,
	          r = o.y;
	      e._isChoosing = !0, e._moveSelectorTo(s - i.left, r - i.top), e._updateColorFromPosition(), e._window.addEventListener("mouseup", e._onSaturationMouseUp), e._window.addEventListener("touchend", e._onSaturationMouseUp), e._window.addEventListener("mousemove", e._onSaturationMouseMove), e._window.addEventListener("touchmove", e._onSaturationMouseMove), t.preventDefault();
	    }, this._onSaturationMouseMove = function (t) {
	      var i = e.$saturation.getBoundingClientRect(),
	          o = n(t);
	      e._moveSelectorTo(o.x - i.left, o.y - i.top), e._updateColorFromPosition();
	    }, this._onSaturationMouseUp = function () {
	      e._isChoosing = !1, e._window.removeEventListener("mouseup", e._onSaturationMouseUp), e._window.removeEventListener("touchend", e._onSaturationMouseUp), e._window.removeEventListener("mousemove", e._onSaturationMouseMove), e._window.removeEventListener("touchmove", e._onSaturationMouseMove);
	    }, this._onHueMouseDown = function (t) {
	      var i = e.$hue.getBoundingClientRect(),
	          o = n(t).y;
	      e._isChoosing = !0, e._moveHueTo(o - i.top), e._updateHueFromPosition(), e._window.addEventListener("mouseup", e._onHueMouseUp), e._window.addEventListener("touchend", e._onHueMouseUp), e._window.addEventListener("mousemove", e._onHueMouseMove), e._window.addEventListener("touchmove", e._onHueMouseMove), t.preventDefault();
	    }, this._onHueMouseMove = function (t) {
	      var i = e.$hue.getBoundingClientRect(),
	          o = n(t);
	      e._moveHueTo(o.y - i.top), e._updateHueFromPosition();
	    }, this._onHueMouseUp = function () {
	      e._isChoosing = !1, e._window.removeEventListener("mouseup", e._onHueMouseUp), e._window.removeEventListener("touchend", e._onHueMouseUp), e._window.removeEventListener("mousemove", e._onHueMouseMove), e._window.removeEventListener("touchmove", e._onHueMouseMove);
	    }, this._window = t.window || window, this._document = this._window.document, this.$el = this._document.createElement("div"), this.$el.className = "Scp", this.$el.innerHTML = '\n      <div class="Scp-saturation">\n        <div class="Scp-brightness"></div>\n        <div class="Scp-sbSelector"></div>\n      </div>\n      <div class="Scp-hue">\n        <div class="Scp-hSelector"></div>\n      </div>\n    ', this.$saturation = this.$el.querySelector(".Scp-saturation"), this.$hue = this.$el.querySelector(".Scp-hue"), this.$sbSelector = this.$el.querySelector(".Scp-sbSelector"), this.$hSelector = this.$el.querySelector(".Scp-hSelector"), this.$saturation.addEventListener("mousedown", this._onSaturationMouseDown), this.$saturation.addEventListener("touchstart", this._onSaturationMouseDown), this.$hue.addEventListener("mousedown", this._onHueMouseDown), this.$hue.addEventListener("touchstart", this._onHueMouseDown), t.el && this.appendTo(t.el), t.background && this.setBackgroundColor(t.background), t.widthUnits && (this._widthUnits = t.widthUnits), t.heightUnits && (this._heightUnits = t.heightUnits), this.setSize(t.width || 175, t.height || 150), this.setColor(t.color);
	  }

	  var e = t.prototype;
	  return e.appendTo = function (t) {
	    return "string" == typeof t ? document.querySelector(t).appendChild(this.$el) : t.appendChild(this.$el), this;
	  }, e.remove = function () {
	    this._callbacks = [], this._onSaturationMouseUp(), this._onHueMouseUp(), this.$saturation.removeEventListener("mousedown", this._onSaturationMouseDown), this.$saturation.removeEventListener("touchstart", this._onSaturationMouseDown), this.$hue.removeEventListener("mousedown", this._onHueMouseDown), this.$hue.removeEventListener("touchstart", this._onHueMouseDown), this.$el.parentNode && this.$el.parentNode.removeChild(this.$el);
	  }, e.setColor = function (t) {
	    this._inputIsNumber = o(t), this.color.fromHex(t);
	    var e = this.color.hsv,
	        i = e.h,
	        s = e.s,
	        n = e.v;
	    return isNaN(i) || (this.hue = i), this._moveSelectorTo(this._saturationWidth * s, (1 - n) * this._hueHeight), this._moveHueTo((1 - this.hue) * this._hueHeight), this._updateHue(), this;
	  }, e.setSize = function (t, e) {
	    return this.width = t, this.height = e, this.$el.style.width = this.width + this._widthUnits, this.$el.style.height = this.height + this._heightUnits, this._saturationWidth = this.width - 25, this.$saturation.style.width = this._saturationWidth + "px", this._hueHeight = this.height, this._maxHue = this._hueHeight - 2, this;
	  }, e.setBackgroundColor = function (t) {
	    return this.backgroundColor.fromHex(t), this.$el.style.padding = "5px", this.$el.style.background = this.backgroundColor.hexString, this;
	  }, e.setNoBackground = function () {
	    return this.$el.style.padding = "0px", this.$el.style.background = "none", this;
	  }, e.onChange = function (t) {
	    return this._callbacks.indexOf(t) < 0 && (this._callbacks.push(t), t(this.getHexString())), this;
	  }, e.getColor = function () {
	    return this._inputIsNumber ? this.getHexNumber() : this.getHexString();
	  }, e.getHexString = function () {
	    return this.color.hexString.toUpperCase();
	  }, e.getHexNumber = function () {
	    return this.color.hex;
	  }, e.getRGB = function () {
	    return this.color.rgb;
	  }, e.getHSV = function () {
	    return this.color.hsv;
	  }, e.isDark = function () {
	    return this.color.isDark;
	  }, e.isLight = function () {
	    return this.color.isLight;
	  }, e._moveSelectorTo = function (t, e) {
	    this.position.x = s(t, 0, this._saturationWidth), this.position.y = s(e, 0, this._hueHeight), this.$sbSelector.style.transform = "translate(" + this.position.x + "px, " + this.position.y + "px)";
	  }, e._updateColorFromPosition = function () {
	    this.color.fromHsv({
	      h: this.hue,
	      s: this.position.x / this._saturationWidth,
	      v: 1 - this.position.y / this._hueHeight
	    }), this._updateColor();
	  }, e._moveHueTo = function (t) {
	    this._huePosition = s(t, 0, this._maxHue), this.$hSelector.style.transform = "translateY(" + this._huePosition + "px)";
	  }, e._updateHueFromPosition = function () {
	    var t = this.getHSV();
	    this.hue = 1 - this._huePosition / this._maxHue, this.color.fromHsv({
	      h: this.hue,
	      s: t.s,
	      v: t.v
	    }), this._updateHue();
	  }, e._updateHue = function () {
	    this.hueColor.fromHsv({
	      h: this.hue,
	      s: 1,
	      v: 1
	    }), this.$saturation.style.background = "linear-gradient(to right, #fff, " + this.hueColor.hexString + ")", this._updateColor();
	  }, e._updateColor = function () {
	    this.$sbSelector.style.background = this.getHexString(), this.$sbSelector.style.borderColor = this.isDark() ? "#fff" : "#000", this._triggerChange();
	  }, e._triggerChange = function () {
	    var t = this;

	    this._callbacks.forEach(function (e) {
	      return e(t.getHexString());
	    });
	  }, i(t, [{
	    key: "isChoosing",
	    get: function get() {
	      return this._isChoosing;
	    }
	  }]), t;
	}();

	insertCss_2(".Scp{-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;position:relative}.Scp-saturation{position:relative;height:100%;background:linear-gradient(90deg,#fff,red);float:left;margin-right:5px}.Scp-brightness{width:100%;height:100%;background:linear-gradient(hsla(0,0%,100%,0),#000)}.Scp-sbSelector{border:2px solid #fff;position:absolute;width:14px;height:14px;background:#fff;border-radius:10px;top:-7px;left:-7px;box-sizing:border-box;z-index:10}.Scp-hue{width:20px;height:100%;position:relative;float:left;background:linear-gradient(red,#f0f 17%,#00f 34%,#0ff 50%,#0f0 67%,#ff0 84%,red)}.Scp-hSelector{position:absolute;background:#fff;border-bottom:1px solid #000;right:-3px;width:10px;height:2px}");

	var hex2rgb = (function (color) {
	  if (!color) throw new Error('Incorrected value');
	  color = color.replace('#', '');
	  if (color.length == 3) color = color[0] + color[0] + color[1] + color[1] + color[2] + color[2];else if (color.length != 6) throw new Error('Incorrected value');
	  return {
	    r: parseInt(color[0] + color[1], 16),
	    g: parseInt(color[2] + color[3], 16),
	    b: parseInt(color[4] + color[5], 16)
	  };
	});

	var rgb2hex = (function (r, g, b) {
	  return ((1 << 24) + (parseInt(r, 10) << 16) + (parseInt(g, 10) << 8) + parseInt(b, 10)).toString(16).slice(1);
	});

	function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { babelHelpers.defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

	function _classPrivateFieldInitSpec(obj, privateMap, value) { _checkPrivateRedeclaration(obj, privateMap); privateMap.set(obj, value); }

	function _checkPrivateRedeclaration(obj, privateCollection) { if (privateCollection.has(obj)) { throw new TypeError("Cannot initialize the same private elements twice on an object"); } }
	var DEFAULT = {
	  containerId: 'colorPickerId',
	  color: 'FFFFFF'
	};

	var _activeSet = /*#__PURE__*/new WeakMap();

	var _activeVariant = /*#__PURE__*/new WeakMap();

	var _activeColor = /*#__PURE__*/new WeakMap();

	var RedsignTuningOptionColor = /*#__PURE__*/function () {
	  babelHelpers.createClass(RedsignTuningOptionColor, [{
	    key: "activeSet",
	    get: function get() {
	      return babelHelpers.classPrivateFieldGet(this, _activeSet);
	    },
	    set: function set(_set) {
	      if (babelHelpers.classPrivateFieldGet(this, _activeSet) == _set) return;
	      this.sets.forEach(function (item) {
	        return item.classList.remove('active');
	      });

	      if (!this.sets.includes(_set)) {
	        babelHelpers.classPrivateFieldGet(this, _activeSet).classList.remove('active');
	        babelHelpers.classPrivateFieldSet(this, _activeSet, null);
	        return;
	      }

	      babelHelpers.classPrivateFieldSet(this, _activeSet, _set);

	      _set.classList.add('active');

	      var setScheme = JSON.parse(_set.getAttribute('data-value'));
	      var keys = Object.keys(setScheme);
	      var activeVariant = null;

	      for (var index in this.variants) {
	        var variant = this.variants[index];
	        var variantKey = variant.getAttribute('data-valkey');

	        if (!keys.includes(variantKey)) {
	          variant.style.display = 'none';
	          continue;
	        }

	        variant.style.display = '';
	        this.setVariantColor(variant, setScheme[variantKey]);

	        if (keys[0] == variantKey) {
	          activeVariant = variant;
	        }
	      }

	      if (activeVariant) {
	        this.activeVariant = activeVariant;
	      }
	    }
	  }, {
	    key: "activeVariant",
	    get: function get() {
	      return babelHelpers.classPrivateFieldGet(this, _activeVariant);
	    },
	    set: function set(variant) {
	      if (babelHelpers.classPrivateFieldGet(this, _activeVariant) == variant) {
	        this.activeColor = variant.querySelector('input').value;
	        return;
	      }

	      this.variants.forEach(function (item) {
	        return item.classList.remove('active');
	      });
	      if (!this.variants.includes(variant)) throw new Error('Incorrected value');
	      babelHelpers.classPrivateFieldSet(this, _activeVariant, variant);
	      variant.classList.add('active');
	      this.activeColor = variant.querySelector('input').value;
	    }
	  }, {
	    key: "activeColor",
	    get: function get() {
	      return babelHelpers.classPrivateFieldGet(this, _activeColor);
	    },
	    set: function set(color) {
	      try {
	        color = color.replace('#', '');
	        if (babelHelpers.classPrivateFieldGet(this, _activeColor) == color) return;
	        babelHelpers.classPrivateFieldSet(this, _activeColor, color);
	        this.setColorFields(color);
	        this.setVariantColor(this.activeVariant, color);
	        if (this.colorPicker) this.colorPicker.setColor(color);
	        var activeScheme = JSON.stringify(this.getActiveScheme()).toUpperCase();
	        var count = 0;

	        for (var index in this.sets) {
	          var setScheme = this.sets[index].getAttribute('data-value').toUpperCase();

	          if (activeScheme == setScheme) {
	            this.activeSet = this.sets[index];
	            break;
	          } else {
	            count++;
	          }
	        }

	        if (count == this.sets.length) {
	          this.activeSet = null;
	        }
	      } catch (e) {
	        console.error(e.message);
	      }
	    }
	  }]);

	  function RedsignTuningOptionColor() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, RedsignTuningOptionColor);
	    babelHelpers.defineProperty(this, "options", {});
	    babelHelpers.defineProperty(this, "container", null);
	    babelHelpers.defineProperty(this, "colorPickerNode", null);
	    babelHelpers.defineProperty(this, "fieldRed", null);
	    babelHelpers.defineProperty(this, "fieldGreen", null);
	    babelHelpers.defineProperty(this, "fieldBlue", null);
	    babelHelpers.defineProperty(this, "fieldHex", null);
	    babelHelpers.defineProperty(this, "colorPicker", null);
	    babelHelpers.defineProperty(this, "variants", []);
	    babelHelpers.defineProperty(this, "sets", []);

	    _classPrivateFieldInitSpec(this, _activeSet, {
	      writable: true,
	      value: null
	    });

	    _classPrivateFieldInitSpec(this, _activeVariant, {
	      writable: true,
	      value: null
	    });

	    _classPrivateFieldInitSpec(this, _activeColor, {
	      writable: true,
	      value: ''
	    });

	    this.options = _objectSpread(_objectSpread({}, DEFAULT), options);
	    document.addEventListener("DOMContentLoaded", this.init.bind(this));
	  }

	  babelHelpers.createClass(RedsignTuningOptionColor, [{
	    key: "init",
	    value: function init() {
	      var _this = this;

	      this.container = document.getElementById(this.options.containerId);
	      if (!this.container) return;
	      this.colorPickerNode = this.container.querySelector('[data-entity="colorpicker"]');
	      this.fieldRed = this.container.querySelector('[data-entity="field-red"]');
	      this.fieldGreen = this.container.querySelector('[data-entity="field-green"]');
	      this.fieldBlue = this.container.querySelector('[data-entity="field-blue"]');
	      this.fieldHex = this.container.querySelector('[data-entity="field-hex"]');
	      this.sets = babelHelpers.toConsumableArray(this.container.querySelectorAll('[data-entity="set-item"]'));
	      this.variants = babelHelpers.toConsumableArray(this.container.querySelectorAll('[data-entity="variant"]'));
	      var activeSet = this.sets.filter(function (set) {
	        return set.classList.contains('active');
	      })[0];
	      if (activeSet) this.activeSet = activeSet;else {
	        this.activeVariant = this.variants.filter(function (variant) {
	          return variant.classList.contains('active');
	        })[0];
	      }

	      if (this.variants) {
	        this.variants.forEach(function (item) {
	          item.addEventListener('click', function (_ref) {
	            var currentTarget = _ref.currentTarget;
	            _this.activeVariant = currentTarget;
	          });
	        });
	      }

	      if (this.sets) {
	        this.sets.forEach(function (item) {
	          item.addEventListener('click', function (_ref2) {
	            var currentTarget = _ref2.currentTarget;
	            _this.activeSet = currentTarget;
	          });
	        });
	      }

	      if (this.fieldHex) {
	        ['blur', 'input'].forEach(function (event) {
	          return _this.fieldHex.addEventListener(event, BX.debounce(function (_ref3) {
	            var target = _ref3.target;
	            _this.activeColor = target.value;
	          }, 500));
	        });
	      }

	      [this.fieldRed, this.fieldGreen, this.fieldBlue].forEach(function (field) {
	        if (!field) return;
	        ['blur', 'input'].forEach(function (event) {
	          return field.addEventListener(event, BX.debounce(function () {
	            _this.activeColor = rgb2hex(_this.fieldRed.value, _this.fieldGreen.value, _this.fieldBlue.value);
	          }, 500));
	        });
	      });

	      if (this.fieldHex) {
	        ['blur', 'keyup'].forEach(function (event) {
	          return _this.fieldHex.addEventListener(event, BX.debounce(function (_ref4) {
	            var target = _ref4.target;
	            _this.activeColor = target.value;
	          }, 500));
	        });
	      }

	      if (this.colorPickerNode) {
	        this.colorPicker = new a({
	          el: this.colorPickerNode,
	          color: this.options.color
	        });

	        if (this.colorPicker) {
	          this.setColorPickerSize();
	          this.colorPicker.onChange(function () {
	            var hex = _this.colorPicker.getHexString();

	            _this.activeColor = hex;
	          });
	          window.addEventListener('resize', BX.debounce(function () {
	            _this.setColorPickerSize();
	          }, 200));
	        }
	      }

	      if (!this.isVisible()) BX.addCustomEvent('rs.tuning.tabs.after.change', this.setColorPickerSize.bind(this));
	    }
	  }, {
	    key: "setColorPickerSize",
	    value: function setColorPickerSize() {
	      if (!this.colorPicker) return;
	      var style = getComputedStyle(this.colorPickerNode);
	      var height = parseInt(style.height, 10);
	      var width = parseInt(style.width, 10);
	      this.colorPicker.setSize(width, height); // fix selector position

	      this.colorPicker.setColor(this.colorPicker.getHexString());
	    }
	  }, {
	    key: "setColorFields",
	    value: function setColorFields(hex) {
	      var rgb = hex2rgb(hex);
	      if (this.fieldRed) this.fieldRed.value = Math.round(rgb.r);
	      if (this.fieldGreen) this.fieldGreen.value = Math.round(rgb.g);
	      if (this.fieldBlue) this.fieldBlue.value = Math.round(rgb.b);
	      if (this.fieldHex) this.fieldHex.value = hex.replace('#', '');
	    }
	  }, {
	    key: "isVisible",
	    value: function isVisible() {
	      return this.container.offsetParent !== null;
	    }
	  }, {
	    key: "setVariantColor",
	    value: function setVariantColor(variant, hex) {
	      var input = variant.querySelector('input');

	      if (input) {
	        input.value = hex;
	        input.dispatchEvent(new Event('change'));
	      }

	      var paint = variant.querySelector('[data-entity="variant-paint"]');
	      if (paint) paint.style.backgroundColor = '#' + hex;
	    }
	  }, {
	    key: "getActiveScheme",
	    value: function getActiveScheme() {
	      var scheme = {};

	      for (var index in this.variants) {
	        scheme[this.variants[index].getAttribute('data-valkey')] = this.variants[index].querySelector('input').value;
	      }

	      return scheme;
	    }
	  }]);
	  return RedsignTuningOptionColor;
	}();

	exports.RedsignTuningOptionColor = RedsignTuningOptionColor;

}((this.window = this.window || {})));
//# sourceMappingURL=script.js.map
