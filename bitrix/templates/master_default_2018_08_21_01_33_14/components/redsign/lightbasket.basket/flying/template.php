<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (CSite::InDir(SITE_DIR.'cart/')) {
  return;
}

Loc::loadMessageS(__FILE__);

$isAjax = isset($arParams['AJAX']) && $arParams['AJAX'] == 'Y' ? true : false;
if ($isAjax) {
    $content = '';
    $mobileCart = '';
}

$jsParams = array(
    'ID' => 'flycart',
    'TEMPLATE_NAME' => $templateName,
    'TEMPLATE_PARAMS' => $arParams,
    'AJAX_PATH' => $componentPath.'/ajax.php',
    'SITE_ID' => SITE_ID
);


if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0) {
    $arrIDs = array();
    foreach ($arResult['ITEMS'] as $arItem) {
        $arrIDs[] = $arItem['PRODUCT_ID'];
    }
}
if (!$isAjax) {
    $frame = $this->createFrame()->begin('');
}
?>
<?php if(!$isAjax): ?>
<div class="flying-cart" id="<?=$jsParams['ID']?>" style="display: none;">
    <div class="flying-cart__icon">
        <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
        <div class="flying-cart__count"><?=count($arResult['ITEMS'])?></div>
    </div>
    <div class="flying-cart__content">
<?php else: ob_start(); endif; ?>
        <div class="b-cart-head b-cart-head--popup">
            <div class="b-cart-head__icon">
                <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
                <svg class="icon-svg b-cart-head__loader"><use xlink:href="#svg-spinner"></use></svg>
            </div>
            <h3 class="b-cart-head__title"><?=Loc::getMessage('RS_LIGHTBASKET_YOUR_CART');?></h3>
            <div class="b-cart-head__close js-cart-close"><a href="#"><?=Loc::getMessage('RS_LIGHTBASKET_CLOSE');?><svg class="icon-svg b-cart__cross"><use xlink:href="#svg-cross"></use></svg></a></div>
        </div>
        <?php if (empty($arResult['ITEMS'])): ?>
            <div class="b-cart-empty">
                <div class="b-cart-empty__icon"><svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg></div>
                <h3 class="b-cart-empty__title"><?=Loc::getMessage('RS_LIGHTBASKET_YOUR_CART_EMPTY');?></h3>
                <div class="b-cart-empty__note"><?=Loc::getMessage('RS_LIGHTBASKET_CART_EMPTY_NOTE');?></div>
                <a class="btn btn-primary b-cart-empty__btn" href="<?=$arParams['PATH_TO_CATALOG']?>"><?=Loc::getMessage('RS_LIGHTBASKET_CART_CATALOG');?></a>
            </div>
        <?php else: ?>
            <div class="b-cart b-cart--popup js-cart">
                <div class="b-cart__products js-cart__products">
                    <table class="table b-cart__table">
                        <thead>
                            <tr>
                                <th class="b-cart__table-name" colspan="2"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_NAME');?></th>
                                <th class="b-cart__table-price"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_PRICE');?></th>
                                <th class="b-cart__table-quantity"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_QUANTITY');?></th>
                                <th class="b-cart__table-sum"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_SUM');?></th>
                                <th class="b-cart__table-tools"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($arResult['ITEMS'] as $id => $arItem): ?>
                            <tr data-id="<?=$arItem['ID']?>" data-product-id="<?=$arItem['PRODUCT_ID']?>">
                                <td class="b-cart__table-name" colspan="2">
                                    <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                                    <a class="b-cart__item-img">
                                        <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                                    </a>
                                    <?php endif; ?>
                                    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-cart__item-name"><?=$arItem['NAME']?></a>
                                </td>
                                <td class="b-cart__table-price">
                                    <?php if ($arItem['DISCOUNT'] > 0): ?>
                                    <div class="product-item-price-current discount"><?=str_replace('#', $arItem['DISCOUNT_PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
                                    <div class="product-item-price-old"><?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?></div>
                                    <?php else: ?>
                                    <span class="product-item-price-current"><?=str_replace('#', $arItem['PRICE_FORMATTED'], $arItem['CURRENCY'])?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="product-item-detail-amount product-item-amount dropdown">
                                        <div class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                            <input class="product-item-amount-field" type="tel" value="<?=$arItem['QUANTITY']?>">
                                            <span class="product-item-amount-measure"><?=Loc::getMessage('RS_LIGHTBASKET_MEASURE_NAME');?></span>
                                            <svg class="product-item-amount-icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
                                        </div>
                                        <ul class="dropdown-menu">
                                            <?php for($i = 1; $i < 10; $i++): ?>
                                                <li class="product-item-amount-var"><a href="javascript:;"><?=$i;?></a></li>
                                            <?php endfor; ?>
                                            <li><a class="product-item-amount-custom" href="javascript:;">10+</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td class="b-cart__table-sum"><span class="product-item-price-current js-cart__fullprice"><?=str_replace('#', $arItem['FULL_PRICE_FORMATTED'], $arItem['CURRENCY'])?></span></td>
                                <td class="b-cart__table-tools">
                                    <a href="#" class="js-cart__remove b-cart__tools-remove">
                                        <svg class="icon-svg"><use xlink:href="#svg-cross"></use></svg>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="b-cart__summary">
                    <div class="b-cart__summary-clear"><a href="javascript:;" class="js-cart__clear"><svg class="icon-svg b-cart__cross"><use xlink:href="#svg-cross"></use></svg><?=Loc::getMessage('RS_LIGHTBASKET_CLEAR_BASKET');?></a></div>
                    <div class="b-cart__summary-price">
                        <?=Loc::getMessage('RS_LIGHTBASKET_SUMMARY')?>:
                        <?php foreach ($arResult['PRICE'] as $arPrice): ?>
                            <b><?=str_replace('#', $arPrice['DISCOUNT_PRICE_FORMATTED'], $arPrice['CURRENCY'])?> </b>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="b-cart__bottom">
                    <a href="#" class="js-cart-close"><?=Loc::getMessage('RS_LIGHTBASKET_CONTINUE');?></a>
                    <a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn btn-primary b-cart__bottom-link"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER');?></a>
                </div>
            </div>
        <?php endif; ?>
        <?php if (!$isAjax): ?>
    </div>
</div>
<script>
Basket.inbasket(<?=json_encode($arrIDs)?>, true);
if (!window.RS) {
  window.RS = {};
}
RS.FlyingCart = new RSFlyingCart(<?=CUtil::PhpToJSObject ($jsParams)?>);
</script>
<?php
else:
    $content = ob_get_clean();

    echo CUtil::PhpToJSObject(array(
        'CONTENT' => $content,
        'COUNT' => count($arResult['ITEMS'])
    ));
endif;

$this->SetViewTarget('rs_mobile_cart');?>
<a class="b-head-icon b-head-icon--cart" href="<?=$arParams['PATH_TO_CART']?>">
    <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
    <span class="b-head-icon__count js-mobile-cart-icon"><?=count($arResult['ITEMS'])?></span>
</a>
<?php $this->EndViewTarget();
