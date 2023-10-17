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

<div class="product-item-detail-info-container product-item-detail-info-container-brand">

    <?php if (is_array($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'])): ?>
        <?php
        /*
        echo implode(' / ', array_map(
            function($sName, $sLink) {
                if (isset($arResult['BRANDS'][$sName])) {
                    $sBrandUrl = $arResult['BRANDS'][$sName]['DETAIL_PAGE_URL'];
                } else {
                    $sBrandUrl = $sLink;
                }
                return '<a href="' . $sBrandUrl . '">' . $sName . '</a>';
            },
            $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'],
            $arResult['PROPERTIES'][$sBrandPropCode]['FILTER_URL']
        ));
        */
        ?>
    <?php else: ?>
        <?php
        if (isset($arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']])) {
            $sBrandUrl = $arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']]['DETAIL_PAGE_URL'];
        } else {
            $sBrandUrl = $arResult['PROPERTIES'][$sBrandPropCode]['FILTER_URL'];
        }
        ?>
        <div class="product-item-detail__brand product-item-brand">
            <?php if ($arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']]['PREVIEW_PICTURE']): ?>
                <a class="product-item-brand__canvas" href="<?=$sBrandUrl?>">
                    <img class="product-item-brand__pic" src="<?=$arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']]['PREVIEW_PICTURE']['RESIZE']['src']?>" alt="<?=$arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']]['PREVIEW_PICTURE']['ALT']?>">
                </a>
            <?php endif; ?>
            <div class="product-item-brand__body">
                <span class=""><?=Loc::getMessage('RS.MASTER.BCE_MASTER.PRODUCT_BRAND')?></span><br>
                <a href="<?=$sBrandUrl?>">
                <?php
                if (isset($arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'])) {
                    echo $arResult['DISPLAY_PROPERTIES'][$sBrandPropCode]['DISPLAY_VALUE'];
                } else {
                    echo $arResult['PROPERTIES'][$sBrandPropCode]['VALUE'];
                }
                ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

    
    <?php if ($arResult['CATALOG'] && $arParams['USE_GIFTS_DETAIL'] == 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')): ?>
        <div class="product-item-detail__gift">
            <?=$APPLICATION->GetViewContent('product-item-detail__gift')?>
        </div>
    <?php endif; ?>

</div>