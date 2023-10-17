(function (exports) {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
	  var inputs = document.body.querySelectorAll('.switch input');
	  babelHelpers.toConsumableArray(inputs).forEach(function (input) {
	    input.addEventListener('change', function (_ref) {
	      var target = _ref.target;
	      var option = target.closest('.switch');
	      if (target.checked) option.classList.remove('active');else option.classList.add('active');
	    });
	  });
	});

}((this.window = this.window || {})));
//# sourceMappingURL=script.js.map
