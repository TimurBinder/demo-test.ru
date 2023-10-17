<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;

?>
<div class="product-item-detail-amount product-item-amount dropdown" style="<?=(!$actualItem['CAN_BUY'] ? 'display: none;' : '')?>"
    data-entity="quantity-block">
    <div class="btn btn-lg btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" id="<?=$itemIds['QUANTITY_MENU']?>">
        <input class="product-item-amount-field" id="<?=$itemIds['QUANTITY_ID']?>" type="tel"
            value="<?=$price['MIN_QUANTITY']?>">
        <span class="product-item-amount-measure" id="<?=$itemIds['QUANTITY_MEASURE']?>">
            <?=$actualItem['ITEM_MEASURE']['TITLE']?>
        </span>
        <?/*<span id="<?=$itemIds['PRICE_TOTAL']?>"></span>*/?>
        <svg class="product-item-amount-icon icon-svg"><use xlink:href="#svg-chevron-down"></use></svg>
    </div>
    <ul class="dropdown-menu" aria-labelledby="<?=$itemIds['QUANTITY_MENU']?>">
        <?php for($i = 1; $i < 10; $i++): ?>
            <li class="product-item-amount-var"><a href="javascript:;"><?=$price['MIN_QUANTITY']*$i;?></a></li>
        <?php endfor; ?>
        <li><a class="product-item-amount-custom" href="javascript:;"><?=$price['MIN_QUANTITY']*10;?>+</a></li>
    </ul>
</div>
<?