<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Application;

if (isset($arParams['DETAIL_IS_USE_CUSTOM_SIDEBAR']) && $arParams['DETAIL_IS_USE_CUSTOM_SIDEBAR'] == 'Y' && !empty($arParams['DETAIL_CUSTOM_SIDEBAR_PATH'])) {
    $APPLICATION->SetPageProperty('sidebar-path', $arParams['DETAIL_CUSTOM_SIDEBAR_PATH']);
    $APPLICATION->SetPageProperty('sidebar_position', 'right');
    $APPLICATION->SetPageProperty('hide_sidebar', 'N');
}

Loc::loadMessages(__FILE__);

if ($arParams["PROPERTY_LANDING_LINK"] != "") {
    $code = $arResult['VARIABLES']['ELEMENT_CODE'];

    $cntIBLOCK_List = 10;
    $cache = new CPHPCache();
    $cache_time = 3600;
    $cache_id = 'arPropLanding'.$code;
    $cache_path = 'arPropLanding';
    if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path)) {
        $res = $cache->GetVars();
        if (is_array($res["arPropLanding"]) && (count($res["arPropLanding"]) > 0))
                $arPropLanding = $res["arPropLanding"];
    }

    if (!is_array($arIBlockListID)) {

        $selectElem = Array(
            "ID",
            "IBLOCK_ID",
            "NAME",
            "PROPERTY_".$arParams["PROPERTY_LANDING_LINK"],
        );
        $filterElem = Array("IBLOCK_ID"=>$arParams["IBLOCK_ID"], "CODE" => $code);
        $res = CIBlockElement::GetList(Array(), $filterElem, false, Array("nPageSize"=>1), $selectElem);
        while($ob = $res->GetNextElement()){
            $arPropLanding = $ob->GetFields();
        }

        if ($cache_time > 0) {
            $cache->StartDataCache($cache_time, $cache_id, $cache_path);
            $cache->EndDataCache(array("arPropLanding"=>$arPropLanding));
        }
    }
}

if ($arPropLanding["PROPERTY_".$arParams["PROPERTY_LANDING_LINK"]."_VALUE"] != ""):

$APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "landings",
        Array(
            "IBLOCK_TYPE" => "",
            "IBLOCK_ID" => $arParams["IBLOCK_ID_LANDING"],
            "ELEMENT_SORT_FIELD" => isset($alfaCSortType) ? $alfaCSortType : $arParams["ELEMENT_SORT_FIELD"],
            "ELEMENT_SORT_ORDER" => isset($alfaCSortToo) ? $alfaCSortToo : $arParams["ELEMENT_SORT_ORDER"],
            "ELEMENT_SORT_FIELD2" => isset($alfaCSortType) ? $arParams["ELEMENT_SORT_FIELD"] : $arParams["ELEMENT_SORT_FIELD2"],
            "ELEMENT_SORT_ORDER2" => isset($alfaCSortType) ? $arParams["ELEMENT_SORT_ORDER"] : $arParams["ELEMENT_SORT_ORDER2"],
            "PROPERTY_CODE" => $arParams["PROPERTY_LANDING"],
            "PROPERTY_CODE_MOBILE" => $arParams["LIST_PROPERTY_CODE_MOBILE"],
            "META_KEYWORDS" => $arParams["LIST_META_KEYWORDS"],
            "META_DESCRIPTION" => $arParams["LIST_META_DESCRIPTION"],
            "BROWSER_TITLE" => $arParams["LIST_BROWSER_TITLE"],
            "SET_LAST_MODIFIED" => $arParams["SET_LAST_MODIFIED"],
            "INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
            "BASKET_URL" => $arParams["BASKET_URL"],
            "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
            "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
            "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
            "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
            "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
            "FILTER_NAME" => $arParams["FILTER_NAME"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "SET_TITLE" => $arParams["SET_TITLE"],
            "MESSAGE_404" => $arParams["~MESSAGE_404"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "SHOW_404" => $arParams["SHOW_404"],
            "FILE_404" => $arParams["FILE_404"],
            "DISPLAY_COMPARE" => $arParams["USE_COMPARE"],
            "PAGE_ELEMENT_COUNT" => 100,
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
                  "SOCIAL_SERVICES" => $arParams["DETAIL_SOCIAL_SERVICES"],

            "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
            "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
            "OFFERS_PROPERTY_CODE" => $arParams["LIST_OFFERS_PROPERTY_CODE"],
            "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
            "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
            "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
            "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
            "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],

            "SECTION_ID" => $arPropLanding["PROPERTY_".$arParams["PROPERTY_LANDING_LINK"]."_VALUE"],
            "SECTION_CODE" => "",
            "SECTION_URL" => "",
            "DETAIL_URL" => "",
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
                  'MESS_BTN_ASK' => $arParams['MESS_BTN_ASK'],

            'USE_ENHANCED_ECOMMERCE' => (isset($arParams['USE_ENHANCED_ECOMMERCE']) ? $arParams['USE_ENHANCED_ECOMMERCE'] : ''),
            'DATA_LAYER_NAME' => (isset($arParams['DATA_LAYER_NAME']) ? $arParams['DATA_LAYER_NAME'] : ''),
            'BRAND_PROPERTY' => (isset($arParams['BRAND_PROPERTY']) ? $arParams['BRAND_PROPERTY'] : ''),

            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
            "ADD_SECTIONS_CHAIN" => "",
            'ADD_TO_BASKET_ACTION' => $basketAction,
                  'ADD_TO_BASKET_ACTION_PRIMARY' => (isset($arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY']) ? $arParams['DETAIL_ADD_TO_BASKET_ACTION_PRIMARY'] : null),
            'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
            'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
            'COMPARE_NAME' => $arParams['COMPARE_NAME'],
            'BACKGROUND_IMAGE' => (isset($arParams['SECTION_BACKGROUND_IMAGE']) ? $arParams['SECTION_BACKGROUND_IMAGE'] : ''),
            'COMPATIBLE_MODE' => (isset($arParams['COMPATIBLE_MODE']) ? $arParams['COMPATIBLE_MODE'] : ''),
            'DISABLE_INIT_JS_IN_COMPONENT' => (isset($arParams['DISABLE_INIT_JS_IN_COMPONENT']) ? $arParams['DISABLE_INIT_JS_IN_COMPONENT'] : ''),

            "TEMPLATE_AJAXID" => $arParams["TEMPLATE_AJAXID"],
            "DISPLAY_PREVIEW_TEXT" => $arParams["LIST_DISPLAY_PREVIEW_TEXT"],
            "PREVIEW_TRUNCATE_LEN" => $arParams["PREVIEW_TRUNCATE_LEN"],
            'COMPOSITE_FRAME' => 'Y',

            'IS_USE_CART' => $arParams['IS_USE_CART'],
            'PRICE_PROP' => $arParams['PRICE_PROP'],
            'DISCOUNT_PROP' => $arParams['DISCOUNT_PROP'],
            'CURRENCY_PROP' => $arParams['CURRENCY_PROP'],
            'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
            //element
            'PATH_TO_CART' => $arParams['PATH_TO_CART'],
            'MESS_BTN_ASK' => $arParams['MESS_BTN_ASK'],
            'LINK_BTN_ASK' => $arParams['LINK_BTN_ASK'],
            'ELEMENT_ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
            'LANDING_ELEMENT_IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'LANDING_ELEMENT_ID' => $arPropLanding['ID'],
            'PROPERTY_CODE_ELEMENT' => $arParams['DETAIL_PROPERTY_CODE'],
            'LANDING_DETAIL_PAGE' => "Y",

        ),
        $component
    );

else :

$arDetailParams = array(
    'DISPLAY_DATE' => $arParams['DISPLAY_DATE'],
    'DISPLAY_NAME' => $arParams['DISPLAY_NAME'],
    'DISPLAY_PICTURE' => $arParams['DISPLAY_PICTURE'],
    'DISPLAY_PREVIEW_TEXT' => $arParams['DISPLAY_PREVIEW_TEXT'],
    'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'FIELD_CODE' => $arParams['DETAIL_FIELD_CODE'],
    'PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
    'DETAIL_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['detail'],
    'SECTION_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['section'],
    'META_KEYWORDS' => $arParams['META_KEYWORDS'],
    'META_DESCRIPTION' => $arParams['META_DESCRIPTION'],
    'BROWSER_TITLE' => $arParams['BROWSER_TITLE'],
    'DISPLAY_PANEL' => $arParams['DISPLAY_PANEL'],
    'SET_TITLE' => $arParams['SET_TITLE'],
    'SET_STATUS_404' => $arParams['SET_STATUS_404'],
    'INCLUDE_IBLOCK_INTO_CHAIN' => $arParams['INCLUDE_IBLOCK_INTO_CHAIN'],
    'ADD_SECTIONS_CHAIN' => $arParams['ADD_SECTIONS_CHAIN'],
    'ACTIVE_DATE_FORMAT' => $arParams['DETAIL_ACTIVE_DATE_FORMAT'],
    'CACHE_TYPE' => $arParams['CACHE_TYPE'],
    'CACHE_TIME' => $arParams['CACHE_TIME'],
    'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
    'USE_PERMISSIONS' => $arParams['USE_PERMISSIONS'],
    'GROUP_PERMISSIONS' => $arParams['GROUP_PERMISSIONS'],
    'DISPLAY_TOP_PAGER' => $arParams['DETAIL_DISPLAY_TOP_PAGER'],
    'DISPLAY_BOTTOM_PAGER' => $arParams['DETAIL_DISPLAY_BOTTOM_PAGER'],
    'PAGER_TITLE' => $arParams['DETAIL_PAGER_TITLE'],
    'PAGER_SHOW_ALWAYS' => 'N',
    'PAGER_TEMPLATE' => $arParams['DETAIL_PAGER_TEMPLATE'],
    'PAGER_SHOW_ALL' => $arParams['DETAIL_PAGER_SHOW_ALL'],
    'CHECK_DATES' => $arParams['CHECK_DATES'],
    'ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
    'ELEMENT_CODE' => $arResult['VARIABLES']['ELEMENT_CODE'],
    'IBLOCK_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
    'USE_SHARE' => $arParams['USE_SHARE'],
    'SHARE_HIDE' => $arParams['SHARE_HIDE'],
    'SHARE_TEMPLATE' => $arParams['SHARE_TEMPLATE'],
    'SHARE_HANDLERS' => $arParams['SHARE_HANDLERS'],
    'SHARE_SHORTEN_URL_LOGIN' => $arParams['SHARE_SHORTEN_URL_LOGIN'],
    'SHARE_SHORTEN_URL_KEY' => $arParams['SHARE_SHORTEN_URL_KEY'],
    'ADD_ELEMENT_CHAIN' => (isset($arParams['ADD_ELEMENT_CHAIN']) ? $arParams['ADD_ELEMENT_CHAIN'] : ''),
    'SHOW_PREVIEW_TEXT' => (isset($arParams['DETAIL_SHOW_PREVIEW_TEXT']) ? $arParams['DETAIL_SHOW_PREVIEW_TEXT'] : 'N'),
    'SHOW_READING_TIME' => (isset($arParams['DETAIL_SHOW_READING_TIME']) ? $arParams['DETAIL_SHOW_READING_TIME'] : 'N'),

    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],

    // news.detail -> sale_promotion
    'MARKER_TEXT_PROPERTY' => isset($arParams['MARKER_TEXT_PROPERTY']) ? $arParams['MARKER_TEXT_PROPERTY'] : '-',
    'MARKER_COLOR_PROPERTY' => isset($arParams['MARKER_COLOR_PROPERTY']) ? $arParams['MARKER_COLOR_PROPERTY'] : '-',
    'SALE_DATE_PROPERTY' => isset($arParams['SALE_DATE_PROPERTY']) ? $arParams['SALE_DATE_PROPERTY'] : '-',

    // news.list -> partners
    'SHOW_TITLE' => $arParams['SHOW_TITLE'],
    'SHOW_DESCRIPTION' => $arParams['SHOW_DESCRIPTION'],
    'SITE_URL_PROP' => $arParams['SITE_URL_PROP'],
    'SITE_DOMAIN_PROP' => $arParams['SITE_DOMAIN_PROP'],
    'COMPANY_PHONE_PROP' => $arParams['COMPANY_PHONE_PROP'],
);

if (count($arParams['DETAIL_PROPERTY_CODE']) > 0) {
    foreach ($arParams['DETAIL_PROPERTY_CODE'] as $arProp) {
        if (isset($arParams['DETAIL_PATH_TO_'.$arProp.'_AREA'])) {
            $arDetailParams['DETAIL_PATH_TO_'.$arProp.'_AREA'] = $arParams['DETAIL_PATH_TO_'.$arProp.'_AREA'];
        }
    }
}

$elementId = $APPLICATION->IncludeComponent('bitrix:news.detail',
    $arParams['DETAIL_TEMPLATE'],
    $arDetailParams,
    $component
);

$APPLICATION->IncludeComponent(
    'bitrix:main.include',
    'back',
    array(
        'AREA_FILE_SHOW' => 'file',
        'PATH' => '/include/empty.php',
        'USE_BACK_BUTTON' => 'Y',
        'BACK_BUTTON_URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news'],
        'USE_SHARE' => $arParams['DETAIL_BACK_USE_SHARE'],
        'SOCIAL_SERVICES' => (isset($arParams['DETAIL_SOCIAL_SERVICES']) ? $arParams['DETAIL_SOCIAL_SERVICES'] : ''),
    )
);

$APPLICATION->ShowViewContent('nd_link_items'); // from bitrix:news.detail

if (!empty($arParams['PATH_TO_BLOCKS_AREA'])) {
    $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '',
        array(
            'AREA_FILE_SHOW' => 'file',
            'PATH' => $arParams['PATH_TO_BLOCKS_AREA'],
            'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
            'IBLOCK_ID' => $arParams['IBLOCK_ID'],
            'ELEMENT_ID' => $elementId,
        )
    );
}

if ($arParams['USE_VK_COMMENTS'] == 'Y') {
    $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '',
        array(
            'AREA_FILE_SHOW' => 'file',
            'PATH' => SITE_DIR.'include/vk_comments.php',
            'VK_API_ID' => $arParams['VK_API_ID'],
            'VK_LIMIT' => $arParams['VK_LIMIT'],
        )
    );
}
endif;
