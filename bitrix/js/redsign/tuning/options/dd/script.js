(function (exports,Sortable) {
	'use strict';

	Sortable = Sortable && Sortable.hasOwnProperty('default') ? Sortable['default'] : Sortable;

	function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

	function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { babelHelpers.defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }
	var DEFAULT = {
	  containerId: '',
	  controlName: '',
	  optionId: '',
	  ajaxReload: true
	};
	var RedsignTuningOptionDnD = /*#__PURE__*/function () {
	  function RedsignTuningOptionDnD() {
	    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
	    babelHelpers.classCallCheck(this, RedsignTuningOptionDnD);
	    babelHelpers.defineProperty(this, "container", null);
	    babelHelpers.defineProperty(this, "elements", []);
	    this.options = _objectSpread(_objectSpread({}, DEFAULT), options);
	    document.addEventListener("DOMContentLoaded", this.init.bind(this));
	  }

	  babelHelpers.createClass(RedsignTuningOptionDnD, [{
	    key: "init",
	    value: function init() {
	      var _this = this;

	      this.container = document.getElementById(this.options.containerId);
	      if (!this.container) return;
	      var elements = babelHelpers.toConsumableArray(this.container.querySelectorAll('.js-rstuning-sortable'));

	      for (var i = 0; i < elements.length; i++) {
	        Sortable.create(elements[i], {
	          handle: '.js-rstuning-sortable-handle',
	          ghostClass: "rstuning__option__dd__ghost",
	          forceFallback: true,
	          fallbackClass: 'rstuning__option__dd__fallback',
	          onEnd: function onEnd(event) {
	            var arSortedResult = []; // rsTuningComponent = new RS.TuningComponent(),
	            // tuningObj = rsTuningComponent.getTuningComponent();
	            // if (this.container && this.options.ajaxReload)
	            // {
	            // 	tuningObj.setAttribute('data-reload', 'Y')
	            // }

	            var elements = _this.container.querySelectorAll('input[name="' + _this.options.controlName + '[]"]');

	            if (elements && elements.length > 0) {
	              for (i = 0; i < elements.length; i++) {
	                arSortedResult.push(elements[i].value);
	              }
	            }

	            var event = new CustomEvent('rs.tuning.option.dd.onEnd', {
	              'detail': {
	                'element': _this.container,
	                'value': arSortedResult
	              }
	            });
	            document.dispatchEvent(event);
	            rsTuningComponent.formSubmit();
	          }
	        });
	      }
	    }
	  }]);
	  return RedsignTuningOptionDnD;
	}();

	exports.RedsignTuningOptionDnD = RedsignTuningOptionDnD;

}((this.window = this.window || {}),BX));
//# sourceMappingURL=script.js.map
