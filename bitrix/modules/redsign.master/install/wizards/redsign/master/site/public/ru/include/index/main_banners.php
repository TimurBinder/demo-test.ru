<?$APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"rs_banners", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_DATE" => "Y",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "Y",
		"DISPLAY_PREVIEW_TEXT" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "#BANNERS_BANNERS_IBLOCK_ID#",
		"IBLOCK_TYPE" => "banners",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "Y",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
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
		"PROPERTY_CODE" => array(
			0 => "LINK",
			1 => "BEFORE_TITLE",
			2 => "TITLE",
			3 => "DESCRIPTION",
			4 => "BUTTON_TEXT",
			5 => "",
		),
		"RS_BACKGROUND_PROPERTY" => "BACKGROUND",
		"RS_BANNER_HEIGHT" => "540px",
		"RS_BANNER_IS_AUTOPLAY" => "N",
		"RS_BANNER_TYPE" => $arParams['RS_BANNER_TYPE'],
		"RS_BUTTON_TEXT_PROPERTY" => "BUTTON_TEXT",
		"RS_DESC_PROPERTY" => "DESCRIPTION",
		"RS_IMG_PROPERTY" => "IMAGE",
		"RS_LINK_PROPERTY" => "LINK",
		"RS_PRICE_PROPERTY" => "-",
		"RS_SIDEBANNERS_IBLOCK_TYPE" => "catalog",
		"RS_TITLE_PROPERTY" => "TITLE",
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
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "rs_banners",
		"RS_SIDEBANNERS_IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"RS_SIDEBANNERS" => "none",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"RS_BEFORE_TITLE_PROPERTY" => "BEFORE_TITLE"
	),
	false
);?>
