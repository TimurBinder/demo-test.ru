<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "sidebar",
    array(
        "ROOT_MENU_TYPE" => "mainsub",
        "CHILD_MENU_TYPE" => "mainsub",
        "MAX_LEVEL" => "3",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => "",
    )
);?>
<?php $APPLICATION->ShowViewContent('site_sidebar'); ?>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "sidebar_buttons", array(
	"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "#SITE_DIR#include/forms/ask/",
		"NAME_SIDE_SVG" => "message",
		"PATH" => "#SITE_DIR#include/sidebar/button_inc1.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_buttons",
	Array(
		"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "N",
		"LINK_SIDE_WIDGET" => "#SITE_DIR#contacts/",
		"NAME_SIDE_SVG" => "location",
		"PATH" => "#SITE_DIR#include/sidebar/button_inc2.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"sidebar_buttons", 
	array(
		"AREA_FILE_SHOW" => "file",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "#SITE_DIR#include/forms/feedback/",
		"NAME_SIDE_SVG" => "pencil",
		"PATH" => "#SITE_DIR#include/sidebar/button_inc3.php",
		"COMPONENT_TEMPLATE" => "sidebar_buttons"
	),
	false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_buttons",
	Array(
		"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "#SITE_DIR#include/forms/recall/",
		"NAME_SIDE_SVG" => "device-mobile",
		"PATH" => "#SITE_DIR#include/sidebar/button_inc4.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"sidebar_information",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "sidebar_information",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#SYSTEM_SIDEBAR_INFORMATION_IBLOCK_ID#",
		"IBLOCK_TYPE" => "system",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "1",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"LINK",1=>"",),
		"RSAUTOCITY_LINK_DOYOUKNOW" => "-",
		"RS_PROPERTY_LINK" => "LINK",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "RAND",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_map",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"COMPONENT_TEMPLATE" => "sidebar_map",
		"EDIT_TEMPLATE" => "",
		"PATH" => "#SITE_DIR#include/sidebar/map.php",
		"RS_LINK" => "#SITE_DIR#contacts/"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"sidebar_banners",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"COMPONENT_TEMPLATE" => "sidebar_banners",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(0=>"",1=>"",),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#BANNERS_SIDE_BANNERS_IBLOCK_ID#",
		"IBLOCK_TYPE" => "banners",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "1",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(0=>"LINK",1=>"TARGET",2=>"",),
		"RS_BLANK" => "TARGET",
		"RS_LINK" => "LINK",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N"
	)
);?><?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_note",
	Array(
		"AREA_FILE_SHOW" => "file",
		"AREA_FILE_SUFFIX" => "inc",
		"EDIT_TEMPLATE" => "",
		"PATH" => "#SITE_DIR#include/sidebar/note.php"
	)
);?>
