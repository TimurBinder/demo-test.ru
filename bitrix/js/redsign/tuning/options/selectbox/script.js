(function (exports) {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
	  var openSelectBox = function openSelectBox(item) {
	    item.classList.remove('closed');
	    item.classList.add('open');
	  };

	  var closeSelectBox = function closeSelectBox(item) {
	    item.classList.remove('open');
	    item.addEventListener('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function (_ref) {
	      var target = _ref.target;
	      target.removeClass('closed');
	    }, {
	      once: true
	    });
	  };

	  var selectboxOpeners = document.body.querySelectorAll('.js-rstuning__selectbox__opener');
	  babelHelpers.toConsumableArray(selectboxOpeners).forEach(function (selectboxOpener) {
	    selectboxOpener.addEventListener('click', function (event) {
	      event.stopPropagation();
	      var selectBox = event.target.closest('.js-rstuning__selectbox');

	      if (selectBox.classList.contains('open')) {
	        closeSelectBox(selectBox);
	      } else {
	        var selectBoxOpened = babelHelpers.toConsumableArray(document.body.querySelectorAll('.js-rstuning__selectbox.open')).filter(function (item) {
	          return item !== selectBox;
	        });
	        selectBoxOpened.forEach(function (selectBox) {
	          closeSelectBox(selectBox);
	        });
	        openSelectBox(selectBox);
	      }
	    });
	  });
	  var selectBoxOptions = document.body.querySelectorAll('.js-rstuning__selectbox__option');
	  babelHelpers.toConsumableArray(selectBoxOptions).forEach(function (selectBoxOption) {
	    selectBoxOption.addEventListener('click', function (event) {
	      event.preventDefault();
	      var selectBoxOption = event.target;

	      if (selectBoxOption.classList.contains('active')) {
	        var selectBox = selectBoxOption.closest('.js-rstuning__selectbox');
	        selectBox.classList.toggle('open');
	      } else {
	        var selectBoxSelect = selectBoxOption.closest('.js-rstuning__selectbox__select'),
	            selectBoxOptionInfo = selectBoxOption.closest('.js-rs_option_info'),
	            selectBoxInput = selectBoxOptionInfo.querySelector('input'),
	            selectBoxValue = selectBoxOptionInfo.querySelector('.js-rstuning__selectbox__value');
	        selectBoxInput.value = selectBoxOption.getAttribute('data-value');
	        selectBoxValue.innerHTML = selectBoxOption.innerHTML;
	        selectBoxInput.dispatchEvent(new Event('change'));
	        selectBoxSelect.querySelector('.js-rstuning__selectbox__option.active').classList.remove('active');
	        selectBoxOption.classList.add('active');

	        var _selectBox = selectBoxOption.closest('.js-rstuning__selectbox');

	        _selectBox.classList.toggle('open');

	        return false;
	      }
	    });
	  });
	  document.body.addEventListener('click', function (event) {
	    var tuningRoot = document.body.querySelector('.js-rstuning');
	    var selectBoxOpened = tuningRoot.querySelectorAll('.js-rstuning__selectbox.open');

	    if (tuningRoot.classList.contains('open')) {
	      var selectBox = event.target.closest('.js-rstuning__selectbox.open');

	      if (selectBox) ; else {
	        babelHelpers.toConsumableArray(selectBoxOpened).forEach(function (selectBox) {
	          closeSelectBox(selectBox);
	        });
	      }
	    } else {
	      babelHelpers.toConsumableArray(selectBoxOpened).forEach(function (selectBox) {
	        closeSelectBox(selectBox);
	      });
	    }
	  });
	});

}((this.window = this.window || {})));
//# sourceMappingURL=script.js.map
