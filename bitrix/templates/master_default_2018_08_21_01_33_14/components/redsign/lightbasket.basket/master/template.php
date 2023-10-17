<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$skipProperties = array();

use \Bitrix\Main\Localization\Loc;

if (count($arResult['ITEMS']) > 0):
?>
<div class="b-cart">
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
                                <input class="product-item-amount-field" id="QUANTITY_INPUT_<?=$arItem['ID']?>" type="tel" value="<?=$arItem['QUANTITY']?>">
                                <span class="product-item-amount-measure"><?=Loc::getMessage('RS_LIGHTBASKET_MEASURE_NAME');?></span>
                                <svg class="product-item-amount-icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
                            </div>
                            <ul class="dropdown-menu">
                                <?php for($i = 1; $i < 10; $i++): ?>
                                    <li class="product-item-amount-var"><a href="javascript:;" onclick="rsCartSelectQuantity(<?=$arItem['ID']?>, <?=$i?>)"><?=$i;?></a></li>
                                <?php endfor; ?>
                                <li><a class="product-item-amount-custom" href="javascript:;" onclick="rsCartSelectQuantity(<?=$arItem['ID']?>, false)">10+</a></li>
                            </ul>
                        </div>
                    </td>
                    <td class="b-cart__table-sum"><span class="product-item-price-current" id="FULL_PRICE_<?=$arItem['ID']?>"><?=str_replace('#', $arItem['FULL_PRICE_FORMATTED'], $arItem['CURRENCY'])?></span></td>
                    <td class="b-cart__table-tools">
                        <a href="<?=$arItem['URL_TO_DELETE']?>" class="b-cart__tools-remove">
                            <svg class="icon-svg"><use xlink:href="#svg-cross"></use></svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="b-cart__summary">
        <div class="b-cart__summary-clear"><a href="<?=$arResult['PATH_TO_CLEAR']?>" class="js-cart__clear"><svg class="icon-svg b-cart__cross"><use xlink:href="#svg-cross"></use></svg><?=Loc::getMessage('RS_LIGHTBASKET_CLEAR_BASKET')?></a></div>
        <div class="b-cart__summary-price">
            <?=Loc::getMessage('RS_LIGHTBASKET_SUMMARY')?>:
            <?php foreach ($arResult['PRICE'] as $arPrice): ?>
              <b><?=str_replace('#', $arPrice['DISCOUNT_PRICE_FORMATTED'], $arPrice['CURRENCY'])?> </b>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="b-cart__bottom">
        <a href="<?=$arResult['PATH_TO_CLEAR']?>" class="js-cart-close"><?=Loc::getMessage('RS_LIGHTBASKET_CONTINUE');?></a>
        <a href="/cart/order/" class="btn btn-primary b-cart__bottom-link"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER');?></a>
    </div>
</div>
<?php else: ?>
    <div class="container-fluid">
        <div class="b-cart-empty">
            <div class="b-cart-empty__icon"><svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg></div>
            <h3 class="b-cart-empty__title"><?=Loc::getMessage('RS_LIGHTBASKET_YOUR_CART_EMPTY');?></h3>
            <div class="b-cart-empty__note"><?=Loc::getMessage('RS_LIGHTBASKET_CART_EMPTY_NOTE');?></div>
            <a class="btn btn-primary b-cart-empty__btn" href="/catalog/"><?=Loc::getMessage('RS_LIGHTBASKET_CART_CATALOG');?></a>
        </div>
    </div>
<?php endif; ?>
