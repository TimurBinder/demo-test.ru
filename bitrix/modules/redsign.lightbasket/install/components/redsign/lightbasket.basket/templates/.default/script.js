function rsLightBasketOnClickQuantityBtn(id, type) {

  var input = BX('QUANTITY_INPUT_' + id);

  if (!input) {
    return false;
  }

  if (type == 'minus') {
    --input.value;
  } else if (type == 'plus') {
    ++input.value;
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
        fullprice = BX('FULL_PRICE_' + item.ID);

    if(input) {
      input.value = item.QUANTITY;
    }

    if(fullprice) {
      fullprice.innerHTML = item.CURRENCY.replace('#', BX.util.number_format(item.FULL_PRICE, 0, '.', ' '));
    }
  });

});
