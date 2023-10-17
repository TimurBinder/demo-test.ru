<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Web\Json;

$this->setFrameMode(true);

$arParams['USE_FILTER'] = (isset($arParams['USE_FILTER']) && $arParams['USE_FILTER'] == 'Y' ? 'Y' : 'N');
$arParams['SECTIONS_SHOW_SIDEBAR'] = (isset($arParams['SECTIONS_SHOW_SIDEBAR']) && $arParams['SECTIONS_SHOW_SIDEBAR'] == 'Y' ? 'Y' : 'N');

$isSidebar = ($arParams["SECTIONS_SHOW_SIDEBAR"] == "Y");
$isFilter = ($arParams['USE_FILTER'] == 'Y');

$obName = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $this->GetEditAreaId($this->randString()));

$request = Application::getInstance()->getContext()->getRequest();
?>
<div class="catalog" id="catalog">
    <?php
    if (isset($arParams['USE_COMMON_SETTINGS_BASKET_POPUP']) && $arParams['USE_COMMON_SETTINGS_BASKET_POPUP'] == 'Y')
    {
        $basketAction = isset($arParams['COMMON_ADD_TO_BASKET_ACTION']) ? $arParams['COMMON_ADD_TO_BASKET_ACTION'] : '';
    }
    else
    {
        $basketAction = isset($arParams['SECTION_ADD_TO_BASKET_ACTION']) ? $arParams['SECTION_ADD_TO_BASKET_ACTION'] : '';
    }
    ?>

<?$ElementID = $APPLICATION->IncludeComponent(
    "bitrix:news.detail",
    "",
    Array(
        "DISPLAY_DATE" => $arParams["DISPLAY_DATE"],
        "DISPLAY_NAME" => $arParams["DISPLAY_NAME"],
        "DISPLAY_PICTURE" => $arParams["DISPLAY_PICTURE"],
        "DISPLAY_PREVIEW_TEXT" => $arParams["DISPLAY_PREVIEW_TEXT"],
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "FIELD_CODE" => $arParams["DETAIL_FIELD_CODE"],
        "PROPERTY_CODE" => $arParams["DETAIL_PROPERTY_CODE"],
        "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
        "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
        "META_KEYWORDS" => $arParams["META_KEYWORDS"],
        "META_DESCRIPTION" => $arParams["META_DESCRIPTION"],
        "BROWSER_TITLE" => $arParams["BROWSER_TITLE"],
        "SET_CANONICAL_URL" => $arParams["DETAIL_SET_CANONICAL_URL"],
        "DISPLAY_PANEL" => $arParams["DISPLAY_PANEL"],
        "SET_LAST_MODIFIED" => 'N',//$arParams["SET_LAST_MODIFIED"],
        // on filter ajax request
        // Warning: Cannot modify header information - headers already sent by (output started at \bitrix\components\bitrix\catalog.smart.filter\component.php:902)
        // in \bitrix\modules\main\lib\httpresponse.php on line 99
        "SET_TITLE" => $arParams["SET_TITLE"],
        "MESSAGE_404" => $arParams["MESSAGE_404"],
        "SET_STATUS_404" => $arParams["SET_STATUS_404"],
        "SHOW_404" => $arParams["SHOW_404"],
        "FILE_404" => $arParams["FILE_404"],
        "INCLUDE_IBLOCK_INTO_CHAIN" => $arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
        "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
        "ACTIVE_DATE_FORMAT" => $arParams["DETAIL_ACTIVE_DATE_FORMAT"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "USE_PERMISSIONS" => $arParams["USE_PERMISSIONS"],
        "GROUP_PERMISSIONS" => $arParams["GROUP_PERMISSIONS"],
        "DISPLAY_TOP_PAGER" => $arParams["DETAIL_DISPLAY_TOP_PAGER"],
        "DISPLAY_BOTTOM_PAGER" => $arParams["DETAIL_DISPLAY_BOTTOM_PAGER"],
        "PAGER_TITLE" => $arParams["DETAIL_PAGER_TITLE"],
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => $arParams["DETAIL_PAGER_TEMPLATE"],
        "PAGER_SHOW_ALL" => $arParams["DETAIL_PAGER_SHOW_ALL"],
        "CHECK_DATES" => $arParams["CHECK_DATES"],
        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
        "IBLOCK_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
        "USE_SHARE" => $arParams["USE_SHARE"],
        "SHARE_HIDE" => $arParams["SHARE_HIDE"],
        "SHARE_TEMPLATE" => $arParams["SHARE_TEMPLATE"],
        "SHARE_HANDLERS" => $arParams["SHARE_HANDLERS"],
        "SHARE_SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
        "SHARE_SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
        "ADD_ELEMENT_CHAIN" => (isset($arParams["ADD_ELEMENT_CHAIN"]) ? $arParams["ADD_ELEMENT_CHAIN"] : ''),

        "CATALOG_IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
        "CATALOG_IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
        "CATALOG_BRAND_PROP" => $arParams["CATALOG_BRAND_PROP"],
        "CATALOG_FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],

        "BRAND_PROP" => $arParams["BRAND_PROP"],
    ),
    $component
);?>

<?php if ($ElementID > 0): ?>

    
            <?php if ($arParams['USE_FILTER'] == 'Y'): ?>

                <?php
                ob_start();

                global $sSmartFilterPath;
                $arResult['VARIABLES']['SMART_FILTER_PATH'] = $sSmartFilterPath;
                ?>

                <div id="catalog-filter">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:catalog.smart.filter",
                    "master",
                    array(
                        "IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
                        "SECTION_ID" => $request->get('section'),
                        "FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
                        "PRICE_CODE" => $arParams["PRICE_CODE"],
                        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                        "CACHE_TIME" => $arParams["CACHE_TIME"],
                        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                        "SAVE_IN_SESSION" => "N",
                        "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
                        "XML_EXPORT" => "Y",
                        "SECTION_TITLE" => "NAME",
                        "SECTION_DESCRIPTION" => "DESCRIPTION",
                        "HIDE_NOT_AVAILABLE" => $arParams["HIDE_NOT_AVAILABLE"],
                        "CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
                        "CURRENCY_ID" => $arParams["CURRENCY_ID"],
                        "SEF_MODE" => "N", //$arParams["SEF_MODE"],
                        //"SEF_RULE" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["smart_filter"],
                        "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
                        "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                        "INSTANT_RELOAD" => $arParams["INSTANT_RELOAD"],
                        "SHOW_ALL_WO_SECTION" => "Y",
                        
                        "SCROLL_PROPS" => $arParams["FILTER_SCROLL_PROPS"],
                        "OFFER_SCROLL_PROPS" => $arParams["OFFER_FILTER_SCROLL_PROPS"],
                        "SEARCH_PROPS" => $arParams["FILTER_SEARCH_PROPS"],
                        "OFFER_SEARCH_PROPS" => $arParams["OFFER_FILTER_SEARCH_PROPS"],
                        "OFFER_TREE_COLOR_PROPS" => $arParams["OFFER_TREE_COLOR_PROPS"],
                        "OFFER_TREE_BTN_PROPS" => $arParams["OFFER_TREE_BTN_PROPS"],
                        //"MODEF_SHOW" => "N",
                        "BRAND_PROP" => $arParams["CATALOG_BRAND_PROP"],
                        "TEMPLATE_AJAXID" => $obName
                    ),
                    $component,
                    array("HIDE_ICONS" => "Y")
                );?>
                </div>

                <?php
                $sHtmlContent = ob_get_clean();
                $APPLICATION->AddViewContent('site_sidebar', $sHtmlContent, 100);
                unset($sHtmlContent);
                ?>

            <?php endif; ?>

        <?php if ($arParams['SHOW_SECTION_DESCRIPTION'] == 'top'): ?>
            <div class="catalog__descr catalog__descr-top">
                <?php $APPLICATION->ShowViewContent("brand-mini"); ?>
            </div>
            <?php
            $APPLICATION->ShowViewContent("brand-full");
            ?>
        <?php endif; ?>

        <?php if ($arParams['USE_FILTER'] == 'Y'): ?>
            <div class="catalog__filter-top filter_top">
                <?php $APPLICATION->ShowViewContent('catalog__filter-top'); ?>
            </div>

            <p class="visible-xs">
                <a class="btn btn-gray visible-xs" data-fancybox="catalog-filter" href="#catalog-filter"><?=Loc::getMessage('RS.MASTER.BC_MASTER.FILTER_SHOW')?></a>
            </p>
            
        <?php endif; ?>

        <?php if ($arParams['USE_SORTER'] == 'Y'): ?>
            <div class="catalog__sorter">
                <?php
                global $alfaCTemplate, $alfaCSortType, $alfaCSortToo, $alfaCOutput;
                // $sSorterPath = $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'/include/template/catalog/catalog.sorter.php';

                // if (file_exists($sSorterPath)) {
                    // include($sSorterPath);
                // }

                $arSorterParams = array();

                if (intval($arParams['SORTER_CNT_TEMPLATES']) > 0) {

                     for ($i = 0; $i < $arParams['SORTER_CNT_TEMPLATES']; $i++) {
                        $arSorterParams['ALFA_CNT_TEMPLATES_'.$i] = $arParams['SORTER_CNT_TEMPLATES_'.$i];
                        $arSorterParams['ALFA_CNT_TEMPLATES_NAME_'.$i] = $arParams['SORTER_CNT_TEMPLATES_NAME_'.$i];
                    }
                }

                $APPLICATION->IncludeComponent(
                    "redsign:catalog.sorter",
                    "master",
                    array(
                        "COMPONENT_TEMPLATE" => "master",
                        "ALFA_ACTION_PARAM_NAME" => $arParams['SORTER_ACTION_PARAM_NAME'],
                        "ALFA_ACTION_PARAM_VALUE" => $arParams['SORTER_ACTION_PARAM_VALUE'],
                        "ALFA_CHOSE_TEMPLATES_SHOW" => $arParams['SORTER_CHOSE_TEMPLATES_SHOW'],
                        "ALFA_DEFAULT_TEMPLATE" => $arParams['SORTER_DEFAULT_TEMPLATE'],
                        "ALFA_SORT_BY_SHOW" => $arParams['SORTER_SORT_BY_SHOW'],
                        "ALFA_SORT_BY_NAME" => $arParams['SORTER_SORT_BY_NAME'],
                        "ALFA_SORT_BY_DEFAULT" => $arParams['SORTER_SORT_BY_DEFAULT'],
                        "ALFA_OUTPUT_OF_SHOW" => $arParams['SORTER_OUTPUT_OF_SHOW'],
                        "ALFA_OUTPUT_OF" => $arParams['SORTER_OUTPUT_OF'],
                        "ALFA_OUTPUT_OF_DEFAULT" => $arParams['SORTER_OUTPUT_OF_DEFAULT'],
                        "ALFA_OUTPUT_OF_SHOW_ALL" => $arParams['SORTER_OUTPUT_OF_SHOW_ALL'],
                        "ALFA_SHORT_SORTER" => $arParams['SORTER_SHORT_SORTER'],
                        "ALFA_CNT_TEMPLATES" => $arParams['SORTER_CNT_TEMPLATES'],
                        "TEMPLATE_AJAXID" => $obName
                    ) + $arSorterParams,
                    $component,
                    array("HIDE_ICONS" => "Y")
                );

                $alfaTemplateRows = false;

                if ($alfaCTemplate == 'vid-1') {

                    $alfaTemplateRows = array_fill (
                        0,
                        $alfaCOutput,
                        array(
                           'VARIANT' => '9',
                           'BIG_DATA' => false
                       )
                    );

                    $alfaTemplateRows = Json::encode($alfaTemplateRows);
                }
                ?>
            </div>
        <?php endif; ?>

            <?php
            $arCatalogSectionParams = array(
                "IBLOCK_TYPE" => $arParams["CATALOG_IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
                "ELEMENT_SORT_FIELD" => isset($alfaCSortType) ? $alfaCSortType : $arParams["ELEMENT_SORT_FIELD"],
                "ELEMENT_SORT_ORDER" => isset($alfaCSortToo) ? $alfaCSortToo : $arParams["ELEMENT_SORT_ORDER"],
                "ELEMENT_SORT_FIELD2" => isset($alfaCSortType) ? $arParams["ELEMENT_SORT_FIELD"] : $arParams["ELEMENT_SORT_FIELD2"],
                "ELEMENT_SORT_ORDER2" => isset($alfaCSortToo) ? $arParams["ELEMENT_SORT_ORDER"] : $arParams["ELEMENT_SORT_ORDER2"],
                "PROPERTY_CODE" => $arParams["CATALOG_PROPERTY_CODE"],
                "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
                // "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
                // "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
                // "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
                // "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
                "INCLUDE_SUBSECTIONS" => 'Y',
                "BASKET_URL" => $arParams["BASKET_URL"],
                "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                "FILTER_NAME" => $arParams["CATALOG_FILTER_NAME"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "SET_TITLE" => 'N', //$arParams["SET_TITLE"],
                // "MESSAGE_404" => $arParams["MESSAGE_404"],
                // "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                // "SHOW_404" => $arParams["SHOW_404"],
                // "FILE_404" => $arParams["FILE_404"],
                "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
                "PAGE_ELEMENT_COUNT" => isset($alfaCOutput) ? $alfaCOutput : $arParams["PAGE_ELEMENT_COUNT"],
                "LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
                "PRICE_CODE" => $arParams["PRICE_CODE"],
                "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],

                "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                "USE_PRODUCT_QUANTITY" => 'N',//$arParams['USE_PRODUCT_QUANTITY'],
                "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],

                "DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE" => $arParams["PAGER_TITLE"],
                "PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
                "PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "PAGER_BASE_LINK_ENABLE" => $arParams["PAGER_BASE_LINK_ENABLE"],
                "PAGER_BASE_LINK" => $arParams["PAGER_BASE_LINK"],
                "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
                "LAZY_LOAD" => $arParams["LAZY_LOAD"],
                "MESS_BTN_LAZY_LOAD" => $arParams["~MESS_BTN_LAZY_LOAD"],
                "LOAD_ON_SCROLL" => $arParams["LOAD_ON_SCROLL"],

                "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                "OFFERS_FIELD_CODE" => $arParams["CATALOG_OFFERS_FIELD_CODE"],
                "OFFERS_PROPERTY_CODE" => $arParams["CATALOG_OFFERS_PROPERTY_CODE"],
                "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                "OFFERS_LIMIT" => $arParams["CATALOG_OFFERS_LIMIT"],

                "SECTION_ID" => "",
                "SECTION_CODE" => "",
                "SECTION_URL" => "", //$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "DETAIL_URL" => "", //$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                "USE_MAIN_ELEMENT_SECTION" => $arParams["USE_MAIN_ELEMENT_SECTION"],
                'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                'HIDE_NOT_AVAILABLE' => $arParams["HIDE_NOT_AVAILABLE"],
                'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],

                'LABEL_PROP' => $arParams['LABEL_PROP'],
                'LABEL_PROP_MOBILE' => $arParams['LABEL_PROP_MOBILE'],
                'LABEL_PROP_POSITION' => $arParams['LIST_LABEL_PROP_POSITION'],
                'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                'PRODUCT_BLOCKS' => $arParams['LIST_PRODUCT_BLOCKS'],
                'PRODUCT_BLOCKS_ORDER' => $arParams['LIST_PRODUCT_BLOCKS_ORDER'],
                'PRODUCT_ROW_VARIANTS' => $alfaTemplateRows ? $alfaTemplateRows : $arParams['LIST_PRODUCT_ROW_VARIANTS'],
                'ENLARGE_PRODUCT' => $arParams['LIST_ENLARGE_PRODUCT'],
                'ENLARGE_PROP' => isset($arParams['LIST_ENLARGE_PROP']) ? $arParams['LIST_ENLARGE_PROP'] : '',
                'SHOW_SLIDER' => $arParams['LIST_SHOW_SLIDER'],
                'SLIDER_INTERVAL' => isset($arParams['LIST_SLIDER_INTERVAL']) ? $arParams['LIST_SLIDER_INTERVAL'] : '',
                'SLIDER_PROGRESS' => isset($arParams['LIST_SLIDER_PROGRESS']) ? $arParams['LIST_SLIDER_PROGRESS'] : '',

                'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
                'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
                'MESS_SHOW_MAX_QUANTITY' => (isset($arParams['~MESS_SHOW_MAX_QUANTITY']) ? $arParams['~MESS_SHOW_MAX_QUANTITY'] : ''),
                'RELATIVE_QUANTITY_FACTOR' => (isset($arParams['RELATIVE_QUANTITY_FACTOR']) ? $arParams['RELATIVE_QUANTITY_FACTOR'] : ''),
                'MESS_RELATIVE_QUANTITY_MANY' => (isset($arParams['~MESS_RELATIVE_QUANTITY_MANY']) ? $arParams['~MESS_RELATIVE_QUANTITY_MANY'] : ''),
                'MESS_RELATIVE_QUANTITY_FEW' => (isset($arParams['~MESS_RELATIVE_QUANTITY_FEW']) ? $arParams['~MESS_RELATIVE_QUANTITY_FEW'] : ''),
                'MESS_BTN_BUY' => (isset($arParams['~MESS_BTN_BUY']) ? $arParams['~MESS_BTN_BUY'] : ''),
                'MESS_BTN_ADD_TO_BASKET' => (isset($arParams['~MESS_BTN_ADD_TO_BASKET']) ? $arParams['~MESS_BTN_ADD_TO_BASKET'] : ''),
                'MESS_BTN_SUBSCRIBE' => (isset($arParams['~MESS_BTN_SUBSCRIBE']) ? $arParams['~MESS_BTN_SUBSCRIBE'] : ''),
                'MESS_BTN_DETAIL' => (isset($arParams['~MESS_BTN_DETAIL']) ? $arParams['~MESS_BTN_DETAIL'] : ''),
                'MESS_NOT_AVAILABLE' => (isset($arParams['~MESS_NOT_AVAILABLE']) ? $arParams['~MESS_NOT_AVAILABLE'] : ''),
                'MESS_BTN_COMPARE' => (isset($arParams['~MESS_BTN_COMPARE']) ? $arParams['~MESS_BTN_COMPARE'] : ''),

                'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
                'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
                'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

                'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
                "ADD_SECTIONS_CHAIN" => 'N',// (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ""),

                'ADD_TO_BASKET_ACTION' => $basketAction,
                'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                'COMPARE_PATH' => '',// $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                'COMPARE_NAME' => $arParams['COMPARE_NAME'],
                'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
                'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
                'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

                "TEMPLATE_AJAXID" => $obName,
                "DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
                "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
                'COMPOSITE_FRAME' => 'Y',

                'IS_USE_CART' => $arParams['IS_USE_CART'],
                'PRICE_PROP' => $arParams['CATALOG_PRICE_PROP'],
                'DISCOUNT_PROP' => $arParams['CATALOG_DISCOUNT_PROP'],
                'CURRENCY_PROP' => $arParams['CATALOG_CURRENCY_PROP'],
                'PRICE_DECIMALS' => $arParams['CATALOG_PRICE_DECIMALS'],
                'SHOW_PARENT_DESCR' => 'Y',
                "BRAND_PROP" => $arParams["CATALOG_BRAND_PROP"],
                "BRAND_IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "BRAND_IBLOCK_BRAND_PROP" => $arParams["BRAND_PROP"],
            );
            
            if ($request->get('section')) {
                $arCatalogSectionParams['SECTION_ID'] = $request->get('section');
                
            } else {
                $arCatalogSectionParams['SHOW_ALL_WO_SECTION'] = 'Y'; // set smart.filter + INCLUDE_SUBSECTIONS=Y = bug
                $arCatalogSectionParams['BY_LINK'] = 'Y';
            }
            ?>

            <?$APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                $arParams['LIST_TEMPLATE'],
                $arCatalogSectionParams,
                $component
            );?>

        <?php if ($arParams['SHOW_SECTION_DESCRIPTION'] == 'bottom'): ?>
            <div class="catalog__descr catalog__descr-bottom">
                <?php $APPLICATION->ShowViewContent('catalog_description'); ?>
            </div>
        <?php endif; ?>

<?php endif; ?>

</div>

<?php
if (!$isSidebar) {
    $APPLICATION->SetPageProperty('hide_sidebar', 'Y');
    $APPLICATION->SetPageProperty('wide_page', 'N');
}
