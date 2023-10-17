function rsCartSelectQuantity(id, quantity) {
  var input = BX('QUANTITY_INPUT_' + id);

  if (!input) {
    return false;
  }

  if (quantity) {
    input.value = quantity;
  } else {
    input.value = '';
    input.focus();
  }

  clearTimeout(window['RS_LIGHTBASKET_TIMEOUT' + id]);

  window['RS_LIGHTBASKET_TIMEOUT' + id] = setTimeout(function() {
    rsLightBasketUpdateQuantity(input, id);
  }, 1000);
}

function rsLightBasketUpdateQuantity(input, id) {
  Basket.updateQuantity(id, input.value);
}

BX.ready(function() {

  BX.addCustomEvent('update.rs_lightbasket', function(item) {
    var input = BX('QUANTITY_INPUT_' + item.ID)
    var fullprice = BX('FULL_PRICE_' + item.ID);
	var summaryPrice = BX('SUMMARY_PRICE');
    var summaryPriceVal = '';
    
    if (input) {
      input.value = item.QUANTITY;
    }

    if (fullprice) {
      fullprice.innerHTML = item.CURRENCY.replace('#', BX.util.number_format(item.FULL_PRICE, 0, '.', ' '));
    }

	if ((item.BASKET_DATA || {}).PRICE) {
      item.BASKET_DATA.PRICE.forEach(function (price) {
        summaryPriceVal += '<b>' +  price.CURRENCY.replace('#', BX.util.number_format(price.DISCOUNT_PRICE, 0, '.', ' ')) + '</b>';

		return false;
      });
	}

	if (summaryPrice && summaryPriceVal) {
	  summaryPrice.innerHTML = summaryPriceVal;
	}
  });

});
