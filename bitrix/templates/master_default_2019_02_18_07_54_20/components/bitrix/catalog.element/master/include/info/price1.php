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
<div class="product-item-detail-info-container product-item-detail-price-container">

    <div class="product-item-detail-info-container-title"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.PRICE')?>:</div>
    
    <span class="product-item-detail-price-current<?=($price['RATIO_PRICE'] < $price['RATIO_BASE_PRICE'] ? ' discount' : '')?>" id="<?=$itemIds['PRICE_ID']?>">
        <?php
        if ($actualItem['CAN_BUY']) {
            echo $price['PRINT_RATIO_PRICE'];
        } else {
            echo Loc::getMessage('RS.MASTER.BCE_MASTER.NO_PRICE');
        }
        ?>
    </span>
    
    <?
    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
    {
        ?>
        <span class="product-item-detail-price-old" id="<?=$itemIds['OLD_PRICE_ID']?>"
            style="display: <?=($showDiscount ? '' : 'none')?>;">
            <?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
        </span>
        <?
    }
    ?>
    
    <?
/*
    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
    {
        ?>
        <div class="item_economy_price" id="<?=$itemIds['DISCOUNT_PRICE_ID']?>"
            style="display: <?=($showDiscount ? '' : 'none')?>;">
            <?
            if ($showDiscount)
            {
                echo Loc::getMessage('CT_BCE_CATALOG_ECONOMY_INFO2', array('#ECONOMY#' => $price['PRINT_RATIO_DISCOUNT']));
            }
            ?>
        </div>
        <?
    }
*/
    ?>
</div>
<?