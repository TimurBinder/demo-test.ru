<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */

use \Bitrix\Main\Localization\Loc;

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/js/basket.js');

$arResult["ITEMS"] = $arResult["CATEGORIES"]['READY'];
$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;

$isAjax = isset($arParams['AJAX']) && $arParams['AJAX'] == 'Y' ? true : false;

$content = '';
$mobileCart = '';

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0) {
    $arrIDs = array();
    foreach ($arResult['ITEMS'] as $arItem) {
        $arrIDs[] = $arItem['PRODUCT_ID'];
    }
}

$jsParams = array(
    'ID' => $cartId,
    'TEMPLATE_NAME' => $templateName,
    'TEMPLATE_PARAMS' => $arParams,
    'AJAX_PATH' => $componentPath.'/ajax.php',
    'SITE_ID' => SITE_ID
);
?>
<div class="flying-cart" id="<?=$jsParams['ID']?>" style="display: none;">
    <?php $frame = $this->createFrame('topline-cart')->begin(); ?>
    <div class="flying-cart__icon">
        <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
        <div class="flying-cart__count"><?=count($arResult['ITEMS'])?></div>
    </div>
    <div class="flying-cart__content">
        <?php include(realpath(dirname(__FILE__)).'/ajax_template.php'); ?>
    </div>
    <script>
        Basket.inbasket(<?=json_encode($arrIDs)?>, true);
        if (!window.RS) {
          window.RS = {};
        }
        RS.FlyingCart = new RSFlyingCart(<?=CUtil::PhpToJSObject($jsParams)?>);
    </script>
    <?php $frame->beginStub(); ?>
    <div class="flying-cart__icon">
        <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
        <div class="flying-cart__count"><?=count($arResult['ITEMS'])?></div>
    </div>
    <?php $frame->end(); ?>
</div>

<?php $this->SetViewTarget('rs_mobile_cart'); ?>
<a class="b-head-icon b-head-icon--cart" href="<?=$arParams['PATH_TO_CART']?>">
    <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
    <span class="b-head-icon__count js-mobile-cart-icon"><?=count($arResult['ITEMS'])?></span>
</a>
<?php $this->EndViewTarget();

$this->SetViewTarget('rs_topline_cart'); ?>
<a href="<?=$arParams['PATH_TO_CART']?>" class="b-topline-cart" id="topline-cart">
<?php
$frame = $this->createFrame('topline-cart')->begin();
$frame->setBrowserStorage(true);
?>
    <svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cart-main"></use></svg><span class="b-topline-cart__text"><?=Loc::getMessage('RS_LIGHTBASKET_CART');?></span><span class="b-topline-cart__count js-cart-count"><?=$arResult['NUM_PRODUCTS']?></span>
<?php $frame->beginStub(); ?>
    <svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-cart-main"></use></svg><span class="b-topline-cart__text"><?=Loc::getMessage('RS_LIGHTBASKET_CART');?></span><span class="b-topline-cart__count js-cart-count">0</span>
<?php $frame->end(); ?>
</a>
<?php $this->EndViewTarget(); ?>
