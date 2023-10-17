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
$IBLOCK_ID = $arResult['PROPERTIES'][$sPropCode]['IBLOCK_ID'];
if (!isset($arSKU[$IBLOCK_ID])){
    $arSKU[$IBLOCK_ID] = CCatalogSKU::GetInfoByProductIBlock($IBLOCK_ID);
}
?>
 <?$APPLICATION->IncludeComponent(
    "bitrix:catalog.recommended.products",
    "master",
    array(
        "LINE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
        "ID" => $arResult['ID'],
        "PROPERTY_LINK" => $sPropCode,
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "BASKET_URL" => $arParams["BASKET_URL"],
        "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
        "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
        "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
        "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
        "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
        "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
        "PAGE_ELEMENT_COUNT" => $arParams["ALSO_BUY_ELEMENT_COUNT"],
        "SHOW_OLD_PRICE" => "Y",//need
        "SHOW_DISCOUNT_PERCENT" => "Y",//need
        "PRICE_CODE" => $arParams["PRICE_CODE"],
        "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
        "PRODUCT_SUBSCRIPTION" => 'N',
        "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
        "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
        "SHOW_NAME" => "Y",
        "SHOW_IMAGE" => "Y",
        "MESS_BTN_BUY" => $arParams['MESS_BTN_BUY'],
        "MESS_BTN_DETAIL" => $arParams["MESS_BTN_DETAIL"],
        "MESS_NOT_AVAILABLE" => $arParams['MESS_NOT_AVAILABLE'],
        "MESS_BTN_SUBSCRIBE" => $arParams['MESS_BTN_SUBSCRIBE'],
        "SHOW_PRODUCTS_".$arParams["IBLOCK_ID"] => "Y",
        "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
        "OFFER_TREE_PROPS_".$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
        "OFFER_TREE_COLOR_PROPS_".$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["OFFER_TREE_COLOR_PROPS"][$arSKU[$IBLOCK_ID]['IBLOCK_ID']],
        "ADDITIONAL_PICT_PROP_".$IBLOCK_ID => $arParams['ADDITIONAL_PICT_PROP'][$arParams['IBLOCK_ID']],
        "PROPERTY_CODE_".$arParams['IBLOCK_ID'] => $arParams["LIST_PROPERTY_CODE"],
        "BRAND_PROP_".$arParams['IBLOCK_ID'] => $arParams['BRAND_PROP'][$arParams['IBLOCK_ID']],
        "ICON_NOVELTY_PROP_".$arParams['IBLOCK_ID'] => $arParams['ICON_NOVELTY_PROP'][$arParams['IBLOCK_ID']],
        "ICON_DEALS_PROP_".$arParams['IBLOCK_ID'] => $arParams['ICON_DEALS_PROP'][$arParams['IBLOCK_ID']],
        "ICON_DISCOUNT_PROP_".$arParams['IBLOCK_ID'] => $arParams['ICON_DISCOUNT_PROP'][$arParams['IBLOCK_ID']],
        "ICON_MEN_PROP_".$arParams['IBLOCK_ID'] => $arParams['ICON_MEN_PROP'][$arParams['IBLOCK_ID']],
        "ICON_WOMEN_PROP_".$arParams['IBLOCK_ID'] => $arParams['ICON_WOMEN_PROP'][$arParams['IBLOCK_ID']],
        "ADDITIONAL_PICT_PROP_".$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams['OFFER_ADDITIONAL_PICT_PROP'],
        "PROPERTY_CODE_".$arSKU[$IBLOCK_ID]['IBLOCK_ID'] => $arParams["LIST_OFFERS_PROPERTY_CODE"],
        "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
        "CURRENCY_ID" => $arParams["CURRENCY_ID"],
        'USE_LIKES' => $arParams['USE_LIKES'],
        'USE_SHARE' => $arParams['USE_SHARE'],
        'SOCIAL_SERVICES' => $arParams['LIST_SOCIAL_SERVICES'],
        'SOCIAL_COUNTER' => $arParams['SOCIAL_COUNTER'],
        'SOCIAL_COPY' => $arParams['SOCIAL_COPY'],
        'SOCIAL_LIMIT' => $arParams['SOCIAL_LIMIT'],
        'SOCIAL_SIZE' => $arParams['SOCIAL_SIZE'],
        'POPUP_DETAIL_VARIABLE' => $arParams['POPUP_DETAIL_VARIABLE'],
    ),
    $component,
    array('HIDE_ICONS' => 'Y')
);?>