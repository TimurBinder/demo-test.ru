<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

$arSections = array();

$arFilter = array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'ACTIVE' => 'Y',
    'DEPTH_LEVEL' => 1
);
$obCache = \Bitrix\Main\Data\Cache::createInstance();
if ($obCache->initCache(3600, serialize($arFilter), '/iblock/news')) {
    $arSections = $obCache->getVars();
} elseif ($obCache->startDataCache()) {
    if (Loader::includeModule('iblock')) {
        if (defined('BX_COMP_MANAGED_CACHE')) {
            $GLOBALS['CACHE_MANAGER']->StartTagCache('/iblock/news');
        }

        $rsSection = Bitrix\Iblock\SectionTable::getList(array(
            'select' => array('ID', 'NAME', 'IBLOCK_ID', 'DEPTH_LEVEL', 'CODE'),
            'filter' => $arFilter
        ));
        $arSections = $rsSection->fetchAll();

        if (defined('BX_COMP_MANAGED_CACHE')) {
          $GLOBALS['CACHE_MANAGER']->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);
          $GLOBALS['CACHE_MANAGER']->EndTagCache();
        }
    }
    $obCache->endDataCache($arSections);
}

if (count($arSections) > 0) {
    foreach ($arSections as $arSection) {
        $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            'files',
            array(
                "IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
                "IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
                "NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
                "SORT_BY1"	=>	$arParams["SORT_BY1"],
                "SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
                "SORT_BY2"	=>	$arParams["SORT_BY2"],
                "SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
                "FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
                "PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
                "DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
                "SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                "IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
                "DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
                "SET_TITLE"	=>	$arParams["SET_TITLE"],
                "SET_STATUS_404" => $arParams["SET_STATUS_404"],
                "INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
                "CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
                "CACHE_TIME"	=>	$arParams["CACHE_TIME"],
                "CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
                "DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
                "PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
                "PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
                "PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
                "PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
                "PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
                "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
                "DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
                "DISPLAY_NAME"	=>	"Y",
                "DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
                "DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
                "PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
                "ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
                "USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
                "GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
                "FILTER_NAME"	=>	$arParams["FILTER_NAME"],
                "HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
                "CHECK_DATES"	=>	$arParams["CHECK_DATES"],
                "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
                'PARENT_SECTION' => $arSection['ID'],

                "PROP_FILE" => $arParams['PROP_FILE'],
                "USE_OWL" => $arParams['USE_OWL'],
                "OWL_AUTOPLAY" => $arParams['OWL_AUTOPLAY'],
                "OWL_CHANGE_DELAY" => $arParams['OWL_CHANGE_DELAY'],
                "OWL_CHANGE_SPEED" => $arParams['OWL_CHANGE_SPEED'],
                "OWL_PC" => $arParams['OWL_PC'],
                "OWL_PHONE" => $arParams['OWL_PHONE'],
                "OWL_TABLET" => $arParams['OWL_TABLET'],
                "COLS_IN_ROW" => $arParams['COLS_IN_ROW'],
                "COL_LG" => $arParams['COL_LG'],
                "COL_MD" => $arParams['COL_MD'],
                "COL_SM" => $arParams['COL_SM'],
                "COL_XS" => $arParams['COL_XS'],
                "RS_TITLE" => $arSection['NAME'],
                "USE_GALLERY" => $arParams['USE_GALLERY']

            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );
    }
} else {
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        'files',
        array(
            "IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
            "IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
            "NEWS_COUNT"	=>	$arParams["NEWS_COUNT"],
            "SORT_BY1"	=>	$arParams["SORT_BY1"],
            "SORT_ORDER1"	=>	$arParams["SORT_ORDER1"],
            "SORT_BY2"	=>	$arParams["SORT_BY2"],
            "SORT_ORDER2"	=>	$arParams["SORT_ORDER2"],
            "FIELD_CODE"	=>	$arParams["LIST_FIELD_CODE"],
            "PROPERTY_CODE"	=>	$arParams["LIST_PROPERTY_CODE"],
            "DETAIL_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
            "SECTION_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "IBLOCK_URL"	=>	$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "DISPLAY_PANEL"	=>	$arParams["DISPLAY_PANEL"],
            "SET_TITLE"	=>	$arParams["SET_TITLE"],
            "SET_STATUS_404" => $arParams["SET_STATUS_404"],
            "INCLUDE_IBLOCK_INTO_CHAIN"	=>	$arParams["INCLUDE_IBLOCK_INTO_CHAIN"],
            "CACHE_TYPE"	=>	$arParams["CACHE_TYPE"],
            "CACHE_TIME"	=>	$arParams["CACHE_TIME"],
            "CACHE_FILTER"	=>	$arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "DISPLAY_TOP_PAGER"	=>	$arParams["DISPLAY_TOP_PAGER"],
            "DISPLAY_BOTTOM_PAGER"	=>	$arParams["DISPLAY_BOTTOM_PAGER"],
            "PAGER_TITLE"	=>	$arParams["PAGER_TITLE"],
            "PAGER_TEMPLATE"	=>	$arParams["PAGER_TEMPLATE"],
            "PAGER_SHOW_ALWAYS"	=>	$arParams["PAGER_SHOW_ALWAYS"],
            "PAGER_DESC_NUMBERING"	=>	$arParams["PAGER_DESC_NUMBERING"],
            "PAGER_DESC_NUMBERING_CACHE_TIME"	=>	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
            "PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
            "DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
            "DISPLAY_NAME"	=>	"Y",
            "DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
            "PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],
            "ACTIVE_DATE_FORMAT"	=>	$arParams["LIST_ACTIVE_DATE_FORMAT"],
            "USE_PERMISSIONS"	=>	$arParams["USE_PERMISSIONS"],
            "GROUP_PERMISSIONS"	=>	$arParams["GROUP_PERMISSIONS"],
            "FILTER_NAME"	=>	$arParams["FILTER_NAME"],
            "HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
            "CHECK_DATES"	=>	$arParams["CHECK_DATES"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            'PARENT_SECTION' => $arSection['ID'],

            "PROP_FILE" => $arParams['PROP_FILE'],
            "USE_OWL" => $arParams['USE_OWL'],
            "OWL_AUTOPLAY" => $arParams['OWL_AUTOPLAY'],
            "OWL_CHANGE_DELAY" => $arParams['OWL_CHANGE_DELAY'],
            "OWL_CHANGE_SPEED" => $arParams['OWL_CHANGE_SPEED'],
            "OWL_PC" => $arParams['OWL_PC'],
            "OWL_PHONE" => $arParams['OWL_PHONE'],
            "OWL_TABLET" => $arParams['OWL_TABLET'],
            "COLS_IN_ROW" => $arParams['COLS_IN_ROW'],
            "COL_LG" => $arParams['COL_LG'],
            "COL_MD" => $arParams['COL_MD'],
            "COL_SM" => $arParams['COL_SM'],
            "COL_XS" => $arParams['COL_XS'],
            "USE_GALLERY" => $arParams['USE_GALLERY']

        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
}
