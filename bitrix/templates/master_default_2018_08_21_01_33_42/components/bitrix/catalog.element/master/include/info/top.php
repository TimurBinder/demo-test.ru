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

<?php
if (
    isset($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]])
    && $arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
    || $arParams['USE_FAVORITE'] == 'Y'
): ?>

    <div class="product-item-detail-info-container">

        <?php /*if ($arParams['USE_FAVORITE'] == 'Y'): ?>
            <div class="product-item-detail__favorite js-favorite favorite" id="<?=$itemIds['FAVORITE_LINK_ID']?>">
                <span class="favorite__text"><?=Loc::getMessage('RS.MASTER.BCE_MASTER.FAVORITE_ADD')?></span>
                <svg class="favorite__icon icon-svg"><use xlink:href="#svg-heart"></use></svg>
            </div>
        <?php endif;*/ ?>

        <?php
        if (
            isset($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]])
            && $arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
        ): ?>
            <span class="product-item-detail__artnumber js_product-article">
                <?=$arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE']?>  
            </span>
       <?php endif; ?>

    </div>

<?php endif; ?>