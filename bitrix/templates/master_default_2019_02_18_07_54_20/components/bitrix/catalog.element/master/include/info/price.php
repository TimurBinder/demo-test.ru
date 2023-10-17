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

$priceCode = array_search($price['PRICE_TYPE_ID'], array_column($arResult['CAT_PRICES'], 'ID', 'CODE'));
$showDiscount = $price['RATIO_PRICE'] < $price['RATIO_BASE_PRICE'];
?>
<div class="product-item-detail-price" data-entity="price" data-price-id="<?=$price['PRICE_TYPE_ID']?>">

    <div class="product-item-detail-price-title">
        <?php
        if (strlen($arResult['CAT_PRICES'][$priceCode]['TITLE']) > 0) {
            echo $arResult['CAT_PRICES'][$priceCode]['TITLE'];
        } else {
            echo Loc::getMessage('RS.MASTER.BCE_MASTER.PRICE');
        }
        ?>:
    </div>
    
    <span class="product-item-detail-price-current<?=($showDiscount ? ' discount' : '')?>"id="<?=$mainId.'_old_price_'.$price['PRICE_TYPE_ID']?>" data-entity="price-current">
        <?php

        $a = strrev($actualItem['PROPERTIES']['PRICE']['VALUE']);
							$a = chunk_split($a, 3, ' ');
							echo strrev($a)." руб."; 
        // если указана хоть одна цена цвета
        /* if (is_array($arResult['PROPERTIES']['PRICE_COLOR']['VALUE']) && count($arResult['PROPERTIES']['PRICE_COLOR']['VALUE']) > 0) {
        	$price_colors_unique = array_unique($arResult['PROPERTIES']['PRICE_COLOR']['VALUE']);
        	if (count($price_colors_unique) > 1) {
        		echo 'В зависимости от цвета';
			}
			else {
		        if ($actualItem['CAN_BUY'] && $price['RATIO_PRICE']>0) {
		            echo $price['PRINT_RATIO_PRICE'];
		        } else {
		            echo Loc::getMessage('RS.MASTER.BCE_MASTER.NO_PRICE');
		        }
			}	
		}
        else {
	        if ($actualItem['CAN_BUY'] && $price['RATIO_PRICE']>0) {
	            echo $price['PRINT_RATIO_PRICE'];
	        } else {
	            echo Loc::getMessage('RS.MASTER.BCE_MASTER.NO_PRICE');
	        }
        } */
        ?>
    </span>
 
    <?
    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
    {
        ?>
        <span class="product-item-detail-price-old" id="<?=$mainId.'_price_'.$price['PRICE_TYPE_ID']?>"
            style="display: <?=($showDiscount ? '' : 'none')?>;" data-entity="price-full">
            <?=($showDiscount ? $price['PRINT_RATIO_BASE_PRICE'] : '')?>
        </span>
        <?
    }
    ?>
    
    <?
    if ($arParams['SHOW_OLD_PRICE'] === 'Y')
    {
        ?>
        <span class="product-item-detail-price-economy" id="<?=$mainId.'_price_discount_'.$price['PRICE_TYPE_ID']?>"
            style="display: <?=($showDiscount ? '' : 'none')?>;" data-entity="price-discount">
            <?
            if ($showDiscount)
            {
                echo Loc::getMessage('CT_BCE_CATALOG_ECONOMY_INFO2', array('#ECONOMY#' => $price['PRINT_RATIO_DISCOUNT']));
            }
            ?>
        </span>
        <?
    }
    ?>
</div>
<?