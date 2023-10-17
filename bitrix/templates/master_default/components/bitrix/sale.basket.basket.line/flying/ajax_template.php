<?php
$isAjax = isset($arParams['AJAX']) && $arParams['AJAX'] == 'Y' ? true : false;

use \Bitrix\Main\Localization\Loc;
$arResult["ITEMS"] = $arResult["CATEGORIES"]['READY'];

ob_start();
?>
<div class="b-cart-head b-cart-head--popup">
    <div class="b-cart-head__icon">
        <svg class="icon-svg icon-svg-cart"><use xlink:href="#svg-cart"></use></svg>
        <svg class="icon-svg b-cart-head__loader"><use xlink:href="#svg-spinner"></use></svg>
    </div>
    <h2 class="b-cart-head__title"><?=Loc::getMessage('RS_LIGHTBASKET_YOUR_CART');?></h2>
    <div class="b-cart-head__close js-cart-close"><a href="#"><?=Loc::getMessage('RS_LIGHTBASKET_CLOSE');?><svg class="icon-svg b-cart__cross"><use xlink:href="#svg-cross"></use></svg></a></div>
</div>
<?php if (empty($arResult['ITEMS'])): ?>
<div class="b-cart-empty">
    <div class="b-cart-empty__icon"><svg class="icon-svg"><use xlink:href="#svg-cart"></use></svg></div>
    <h2 class="b-cart-empty__title"><?=Loc::getMessage('RS_LIGHTBASKET_YOUR_CART_EMPTY');?></h2>
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
                    <?php if ($arParams["SHOW_PRICE"] == "Y"): ?>
                    <th class="b-cart__table-price"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_PRICE');?></th>
                    <?php endif; ?>
                    <?php if ($arParams["SHOW_SUMMARY"] == "Y"): ?>
                    <th class="b-cart__table-quantity"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_QUANTITY');?></th>
                    <th class="b-cart__table-sum"><?=Loc::getMessage('RS_LIGHTBASKET_TABLE_SUM');?></th>
                    <?php endif; ?>
                    <th class="b-cart__table-tools"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($arResult['ITEMS'] as $index => $arItem): ?>
                    <tr data-id="<?=$arItem['ID']?>" data-product-id="<?=$arItem['PRODUCT_ID']?>">
                        <td class="b-cart__table-name" colspan="2">
                            <?php if (!empty($arItem['PICTURE_SRC'])): ?>
                            <a class="b-cart__item-img" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                                <img src="<?=$arItem['PICTURE_SRC']?>" alt="<?=$arItem['NAME']?>" title="<?=$arItem['NAME']?>">
                            </a>
                            <?php endif; ?>
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-cart__item-name"><?=$arItem['NAME']?></a>
                        </td>
                        <?php if ($arParams["SHOW_PRICE"] == "Y"): ?>
                        <td class="b-cart__table-price">
                            <?php if ($arItem['FULL_PRICE'] != $arItem['PRICE_FMT']): ?>
                            <div class="product-item-price-current discount"><?=$arItem['PRICE_FMT']?></div>
                            <div class="product-item-price-old"><?=$arItem['FULL_PRICE']?></div>
                            <?php else: ?>
                            <span class="product-item-price-current"><?=$arItem['PRICE_FMT']?></span>
                            <?php endif; ?>
                        </td>
                        <?php endif; ?>
                        <?php if ($arParams["SHOW_SUMMARY"] == "Y"): ?>
                        <td>
                            <div class="product-item-detail-amount product-item-amount dropdown">
                                <div class="btn btn-lg btn-gray dropdown-toggle" data-toggle="dropdown" style="position:relative;">
                                    <input class="product-item-amount-field" type="tel" value="<?=$arItem['QUANTITY']?>">
                                    <span class="product-item-amount-measure"><?=Loc::getMessage('RS_LIGHTBASKET_MEASURE_NAME');?></span>
                                    <svg class="dropdown__icon icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
                                </div>
                                <ul class="dropdown-menu">
                                    <?php for($i = 1; $i < 10; $i++): ?>
                                        <li class="product-item-amount-var"><a href="javascript:;"><?=$i;?></a></li>
                                    <?php endfor; ?>
                                    <li><a class="product-item-amount-custom" href="javascript:;">10+</a></li>
                                </ul>
                            </diV>
                        </td>
                        <td class="b-cart__table-sum"><span class="product-item-price-current js-cart__fullprice"><?=$arItem['SUM']?></span></td>
                        <?php endif; ?>
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
        <div class="b-cart__summary-price"><?=Loc::getMessage('RS_LIGHTBASKET_SUMMARY')?>: <b><?=$arResult['TOTAL_PRICE'];?></b></div>
    </div>
    <div class="b-cart__bottom">
        <a href="#" class="js-cart-close"><?=Loc::getMessage('RS_LIGHTBASKET_CONTINUE');?></a>
        <a href="<?=$arParams['PATH_TO_ORDER']?>" class="btn btn-primary b-cart__bottom-link"><?=Loc::getMessage('RS_LIGHTBASKET_ORDER');?></a>
    </div>
</div>
<?php endif;

$content = ob_get_clean();

if ($isAjax) {
    echo CUtil::PhpToJSObject(array(
      'CONTENT' => $content,
      'COUNT' => count($arResult['ITEMS'])
    ));
} else {
    echo $content;
}
