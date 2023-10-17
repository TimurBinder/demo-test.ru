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
use Bitrix\Main\Loader;

$this->setFrameMode(true);


$arFilter = array(
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    'ACTIVE' => 'Y',
    'GLOBAL_ACTIVE' => 'Y',
);
if (0 < intval($arResult['VARIABLES']['SECTION_ID']))
    $arFilter['ID'] = $arResult['VARIABLES']['SECTION_ID'];
elseif ('' != $arResult['VARIABLES']['SECTION_CODE'])
    $arFilter['=CODE'] = $arResult['VARIABLES']['SECTION_CODE'];

$obCache = new CPHPCache();
if ($obCache->InitCache(36000, serialize($arFilter), '/iblock/catalog'))
{
    $arCurSection = $obCache->GetVars();
}
elseif ($obCache->StartDataCache())
{
    $arCurSection = array();
    if (Loader::includeModule('iblock'))
    {
        $dbRes = CIBlockSection::GetList(array(), $arFilter, false, array('ID', 'LEFT_MARGIN', 'RIGHT_MARGIN'));

        if(defined('BX_COMP_MANAGED_CACHE'))
        {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache('/iblock/catalog');

            if ($arCurSection = $dbRes->Fetch())
                $CACHE_MANAGER->RegisterTag('iblock_id_'.$arParams['IBLOCK_ID']);

            $CACHE_MANAGER->EndTagCache();
        }
        else
        {
            if(!$arCurSection = $dbRes->Fetch())
                $arCurSection = array();
        }
    }
    $obCache->EndDataCache($arCurSection);
}
if (!isset($arCurSection))
    $arCurSection = array();

if (
    $arParams['IBLOCK_VIEW_MODE'] == 'VIEW_SECTIONS'
    && ($arCurSection['RIGHT_MARGIN'] - $arCurSection['LEFT_MARGIN']) > 1
):

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.sections.top",
        $arParams['SECTIONS_TEMPLATE'],
        Array(
            "ACTION_VARIABLE" => "action",
            "BASKET_URL" => "/cart/",
            "CACHE_FILTER" => $arParams["CACHE_FILTER"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["detail"],
            "DISPLAY_COMPARE" => "N",
            "ELEMENT_COUNT" => $arParams["NEWS_COUNT"],
            "ELEMENT_SORT_FIELD" => $arParams["SORT_BY1"],
            "ELEMENT_SORT_FIELD2" => $arParams["SORT_ORDER1"],
            "ELEMENT_SORT_ORDER" => $arParams["SORT_BY2"],
            "ELEMENT_SORT_ORDER2" => $arParams["SORT_ORDER2"],
            "FILTER_NAME"	=>	$arParams["FILTER_NAME"],
            "IBLOCK_ID"	=>	$arParams["IBLOCK_ID"],
            "IBLOCK_TYPE"	=>	$arParams["IBLOCK_TYPE"],
            "LINE_ELEMENT_COUNT" => "3",
            "PRICE_CODE" => array(),
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array(),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PROPERTY_CODE" => $arParams["LIST_PROPERTY_CODE"],
            "SECTION_COUNT" => "20",
            "SECTION_FIELDS" => array("", ""),
            "SECTION_ID_VARIABLE" => "SECTION_ID",
            "SECTION_SORT_FIELD" => "sort",
            "SECTION_SORT_ORDER" => "asc",
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "SECTION_USER_FIELDS" => array("", ""),
            "SHOW_PRICE_COUNT" => "1",
            "USE_MAIN_ELEMENT_SECTION" => "Y",
            "USE_PRICE_COUNT" => "N",
            "USE_PRODUCT_QUANTITY" => "N",

            "SET_TITLE"	=>	$arParams["SET_TITLE"],
            "ADD_SECTIONS_CHAIN" => $arParams["ADD_SECTIONS_CHAIN"],
            "HIDE_LINK_WHEN_NO_DETAIL"	=>	$arParams["HIDE_LINK_WHEN_NO_DETAIL"],
            "CHECK_DATES"	=>	$arParams["CHECK_DATES"],
            "DISPLAY_DATE"	=>	$arParams["DISPLAY_DATE"],
            "DISPLAY_NAME"	=>	"Y",
            "DISPLAY_PICTURE"	=>	$arParams["DISPLAY_PICTURE"],
            "DISPLAY_PREVIEW_TEXT"	=>	$arParams["DISPLAY_PREVIEW_TEXT"],
            "PREVIEW_TRUNCATE_LEN"	=>	$arParams["PREVIEW_TRUNCATE_LEN"],

            "SHOW_TITLE" => $arParams["SHOW_TITLE"],
            "SHOW_DESCRIPTION" => $arParams["SHOW_DESCRIPTION"],
            "SECTION_ID" => $arResult['VARIABLES']['SECTION_ID'],

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
            
            // template -> partners
            "SITE_URL_PROP" => $arParams["SITE_URL_PROP"],
            "SITE_DOMAIN_PROP" => $arParams["SITE_DOMAIN_PROP"],
            "COMPANY_PHONE_PROP" => $arParams["COMPANY_PHONE_PROP"],
            
            // template -> staff
            "PROP_NAME" => $arParams["PROP_NAME"],
            "PROP_POSITION" => $arParams["PROP_POSITION"],
            "PROP_DESCRIPTION" => $arParams["PROP_DESCRIPTION"],
            "PROP_CONTACTS" => $arParams["PROP_CONTACTS"],
            "PROP_SOCIAL" => $arParams["PROP_SOCIAL"],
            "PROP_IS_ASK" => $arParams["PROP_IS_ASK"],
            "ASK_LINK" => $arParams["ASK_LINK"],
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );

else:

    if ($arParams['USE_ARCHIVE'] == 'Y') {

        $arParams["FILTER_NAME"] = trim($arParams["FILTER_NAME"]);
        if ($arParams["FILTER_NAME"] === '' || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"])) {
          $arParams["FILTER_NAME"] = "arrFilter";
        }

        $APPLICATION->IncludeComponent(
            "redsign:news.archive",
            "buttons",
            array(
                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "FILTER_NAME" => $arParams["FILTER_NAME"],
                "CHECK_DATES" => $arParams["CHECK_DATES"],
                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                "CACHE_TIME" => $arParams["CACHE_TIME"],
                "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                "ACTIVE_DATE_FORMAT" => $arParams["LIST_ACTIVE_DATE_FORMAT"],
                "SHOW_YEARS" => 'Y', //$arParams["ARCHIVE_SHOW_YEARS"],
                "SHOW_MONTHS" => 'N', //$arParams["ARCHIVE_SHOW_MONTHS"],
                "SEF_FOLDER" => $arResult["FOLDER"],
                "ARCHIVE_URL" => $arResult["FOLDER"].$arParams["ARCHIVE_URL"],
                "SEF_MODE" => $arParams["SEF_MODE"],
            ),
            $component
        );
    }

    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        $arParams['LIST_TEMPLATE'],
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
            
            "PARENT_SECTION" => $arResult['VARIABLES']['SECTION_ID'],
            "PARENT_SECTION_CODE" => $arResult['VARIABLES']['SECTION_CODE'],
            
            "SHOW_TITLE" => $arParams["SHOW_TITLE"],
            "SHOW_DESCRIPTION" => $arParams["SHOW_DESCRIPTION"],

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

            // news.list -> sale_promotions
            'MARKER_TEXT_PROPERTY' => isset($arParams['MARKER_TEXT_PROPERTY']) ? $arParams['MARKER_TEXT_PROPERTY'] : '-',
            'MARKER_COLOR_PROPERTY' => isset($arParams['MARKER_COLOR_PROPERTY']) ? $arParams['MARKER_COLOR_PROPERTY'] : '-',
            'SALE_DATE_PROPERTY' => isset($arParams['SALE_DATE_PROPERTY']) ? $arParams['SALE_DATE_PROPERTY'] : '-',
            
            // news.list -> partners
            "SITE_URL_PROP" => $arParams["SITE_URL_PROP"],
            "SITE_DOMAIN_PROP" => $arParams["SITE_DOMAIN_PROP"],
            "COMPANY_PHONE_PROP" => $arParams["COMPANY_PHONE_PROP"],
            
            // template -> staff
            "PROP_NAME" => $arParams["PROP_NAME"],
            "PROP_POSITION" => $arParams["PROP_POSITION"],
            "PROP_DESCRIPTION" => $arParams["PROP_DESCRIPTION"],
            "PROP_CONTACTS" => $arParams["PROP_CONTACTS"],
            "PROP_SOCIAL" => $arParams["PROP_SOCIAL"],
            "PROP_IS_ASK" => $arParams["PROP_IS_ASK"],
            "ASK_LINK" => $arParams["ASK_LINK"],
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );

endif;