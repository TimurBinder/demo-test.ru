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
use \Bitrix\Main\ModuleManager;
?>

<div class="product-item-detail-info-container">
   
    <?php
    if (
        isset($actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]) &&
        $actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE'] != ''
    ) {
        ?>
        <span class="product-item-detail__artnumber" data-entity="sku-prop-<?=$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['ID']?>">
            <?=$actualItem['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$actualItem['IBLOCK_ID']]]['VALUE']?>
        </span>
        <?php
    }
    elseif (
        isset($arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]) &&
        $arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE'] != ''
    ) {
        ?>
        <span class="product-item-detail__artnumber">
            <?=$arResult['PROPERTIES'][$arParams['ARTNUMBER_PROP'][$arResult['IBLOCK_ID']]]['VALUE']?>
        </span>
        <?php
    }
    ?>

    <?php
    if ($arParams['USE_VOTE_RATING'] === 'Y')
    {
        ?><div class="product-item-detail__rate"><?
        $APPLICATION->IncludeComponent(
            'bitrix:iblock.vote',
            'stars',
            array(
                'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'ELEMENT_ID' => $arResult['ID'],
                'ELEMENT_CODE' => '',
                'MAX_VOTE' => '5',
                'VOTE_NAMES' => array('1', '2', '3', '4', '5'),
                //'MAX_VOTE' => '10',
                //'VOTE_NAMES' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10'),
                'SET_STATUS_404' => 'N',
                'DISPLAY_AS_RATING' => $arParams['VOTE_DISPLAY_AS_RATING'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME']
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
        ?></div><?
    }
    ?>

    <?php
    if ($arParams['USE_STORE'] == 'Y' && ModuleManager::isModuleInstalled('catalog')) {
        ?>
        <div class="product-item-detail__stock">
            <span><?=Loc::getMessage('RS.MASTER.BCE_MASTER.PRODUCT_AMOUNT')?></span>
            <?=$APPLICATION->GetViewContent('product-item-detail__stock')?>
        </div>
        <?php
    } else {
        
    }
    ?>

</div>