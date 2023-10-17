<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
$prefix = $arParams['PREFIX'];

$arJSONResult = array();

?>
<div class="product-delivery media">
    <div class="product-delivery__pic media-left media-middle hidden-xs">
        <div class="product-delivery__icon"></div>
    </div>
    <div class="product-delivery__body media-body">
        <?php if ($arParams['AJAX_CALL'] != 'Y'): ?>
            <div id="<?=$prefix?>delivery_block" class="product-delivery__block">
                <div class="product-delivery__title"><?=Loc::getMessage('RSDC_TEMPLATE_DELIVERY')?></div>
                <ul class="product-delivery__list">
                    <li><?=Loc::getMessage('RSDC_TEMPLATE_LOADING')?></li>
                </ul>
            </div>
            <?php
                $arAjaxParams = array(
                    'templateName' => $this->GetName(),
                    'siteId' => SITE_ID,
                    'arParams' => $arResult['ORIGINAL_PARAMS'],
                );
                $ajaxPath = $templateFolder.'/ajax.php';
            ?>
            <script>

            BX.addCustomEvent('rs_delivery_update', function(productId, quantity, beforeFn, afterFn) {
              var params = <?=CUtil::PhpToJSObject($arAjaxParams)?>;
              params.arParams.ELEMENT_ID = productId || params.arParams.ELEMENT_ID;
              params.arParams.QUANTITY = quantity || params.arParams.QUANTITY;
              beforeFn = beforeFn || function() {};
              afterFn = afterFn || function() {};

              beforeFn();

              BX.ajax.post('<?=$ajaxPath?>', params, function(result) {

                afterFn();

                var deliveryBlock = BX("<?=$prefix?>delivery_block");
                if (deliveryBlock && result) {
                  deliveryBlock.innerHTML = result;

                  deliveryList = BX("<?=$prefix?>delivery_block_list");

                  deliveryList.style.left = '0px';
                  setTimeout(function() {
                      deliveryList.style.position = 'static';
                  }, 600);
                  window.deliveryList = deliveryList;
                }
              });
            });
            BX.onCustomEvent('rs_delivery_update');

            </script>
        <?php elseif ($arParams['AJAX_CALL'] == 'Y'): $APPLICATION->RestartBuffer(); ?>
            <div class="product-delivery__title">
                <?=Loc::getMessage('RSDC_TEMPLATE_DELIVERY')?>
                <?php
                    if (isset($arResult['LOCATION_TO']['LOCATION_NAME'])) {
                        echo Loc::getMessage('RSDC_TEMPLATE_DELIVERY_IN_CITY').' '.$arResult['LOCATION_TO']['LOCATION_NAME'].': ';
                    }
                ?>
            </div>
			<ul class="product-delivery__list" id="<?=$prefix?>delivery_block_list" style="position: absolute; left: -9999999px;">
            <?php if (count($arResult['DELIVERIES']) > 0): ?>

                <?php foreach ($arResult['DELIVERIES'] as $arDelivery): ?>
                    <?php if ($arDelivery['CALCULATION']['IS_SUCCESS']): ?>
                      <li>
                        <?=$arDelivery['NAME']?> - <?=$arDelivery['CALCULATION']['FORMAT_PRICE'] ?>
                        <?php if ($arDelivery['CALCULATION']['PERIOD']): ?>
                          (<?=$arDelivery['CALCULATION']['PERIOD']?>)
                        <?php endif; ?>
                      </li>
                    <?php endif; ?>
                <?php endforeach; ?>
                <?php if (isset($arParams['SHOW_DELIVERY_PAYMENT_INFO']) && $arParams['SHOW_DELIVERY_PAYMENT_INFO'] == 'Y'):?>
                    <?php
                    $deliveryData = Loc::getMessage('RSDC_TEMPLATE_DELIVERY_DATA');
                    $deliveryData = str_replace('#DELIVERY_LINK#', $arParams['DELIVERY_LINK'], $deliveryData);
                    $deliveryData = str_replace('#PAYMENT_LINK#', $arParams['PAYMENT_LINK'], $deliveryData);
                    ?>
                    <li><br><?=$deliveryData?></li>
                <?php endif; ?>
                
            <?php else: ?>
				<li><?=Loc::getMessage('RSDC_TEMPLATE_DELIVERY_NOT_FOUND')?></li>
            <?php endif; ?>
			</ul>
        <?php die(); endif; ?>

    </div>
</div>
