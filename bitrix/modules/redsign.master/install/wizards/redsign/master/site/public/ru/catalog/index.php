<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?><?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	"master", 
	array(
		"COMPONENT_TEMPLATE" => "master",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
		),
		"LABEL_PROP_MOBILE" => array(
			0 => "NEWPRODUCT",
			1 => "SALELEADER",
			2 => "SPECIALOFFER",
		),
		"LIST_LABEL_PROP_POSITION" => "bottom-left",
		"DETAIL_LABEL_PROP_POSITION" => "top-left",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_ADD_TO_BASKET" => "Купить",
		"MESS_BTN_COMPARE" => "В сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "нет",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"ARTNUMBER_PROP" => "ARTNUMBER",
		"PRICE_PROP" => "PRICE",
		"DISCOUNT_PROP" => "DISCOUNT",
		"CURRENCY_PROP" => "CURRENCY",
		"PRICE_DECIMALS" => "0",
		"SHOW_OLD_PRICE" => "Y",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "#SITE_DIR#catalog/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "Y",
		"DETAIL_STRICT_SECTION_CHECK" => "Y",
		"SET_LAST_MODIFIED" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTIONS_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"ADD_CONTAINER" => "N",
		"IS_USE_CART" => "Y",
		"USE_FAVORITE" => "Y",
		"MESS_BTN_FAVORITE" => "В избранное",
		"FAVORITE_COUNT_PROP" => "FAVORITE_COUNT",
		"USE_FILTER" => "Y",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "Y",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"USE_COMPARE" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
			1 => "OPT",
			2 => "VIP",
		),
		"USE_PRICE_COUNT" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"BASKET_URL" => "#SITE_DIR##CART_PATH#",
		"USE_PRODUCT_QUANTITY" => "Y",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRODUCT_PROPERTIES" => array(
		),
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "ADD",
			1 => "ASK",
		),
		"SEARCH_PAGE_RESULT_COUNT" => "50",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"SHOW_TOP_ELEMENTS" => "Y",
		"TOP_ELEMENT_COUNT" => "9",
		"TOP_LINE_ELEMENT_COUNT" => "3",
		"TOP_ELEMENT_SORT_FIELD" => "sort",
		"TOP_ELEMENT_SORT_ORDER" => "asc",
		"TOP_ELEMENT_SORT_FIELD2" => "id",
		"TOP_ELEMENT_SORT_ORDER2" => "desc",
		"TOP_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "2",
		"SECTIONS_SHOW_PARENT_NAME" => "Y",
		"CATALOG_VIEW_MODE" => "VIEW_SECTIONS",
		"SECTIONS_VIEW_MODE" => "LINE",
		"PAGE_ELEMENT_COUNT" => "12",
		"LINE_ELEMENT_COUNT" => "3",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "BRAND_REF",
			1 => "",
		),
		"LIST_PROPERTY_CODE_MOBILE" => array(
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"SECTION_BACKGROUND_IMAGE" => "UF_BACKGROUND_IMAGE",
		"SHOW_SECTION_DESCRIPTION" => "top",
		"LIST_PRODUCT_BLOCKS" => array(
			0 => "price",
			1 => "buttons",
			2 => "preview",
		),
		"LIST_PRODUCT_BLOCKS_ORDER" => "preview,price,quantity,buttons",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "ARTNUMBER",
			1 => "BRAND_REF",
			2 => "HTML_VIDEO",
			3 => "ROOM_NUMBER",
			4 => "PRICE_TABLE",
			5 => "COLOR_REF",
			6 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_SET_CANONICAL_URL" => "Y",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"SHOW_DEACTIVATED" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => array(
			0 => "ARTNUMBER",
			1 => "BRAND_REF",
			2 => "ROOM_NUMBER",
			3 => "COLOR_REF",
		),
		"DETAIL_USE_VOTE_RATING" => "Y",
		"DETAIL_USE_COMMENTS" => "Y",
		"DETAIL_BRAND_USE" => "Y",
		"DETAIL_DISPLAY_NAME" => "Y",
		"DETAIL_PRODUCT_INFO_BLOCK" => array(
			0 => "props",
			1 => "price",
			2 => "buttons",
			3 => "preview",
			4 => "deals",
			5 => "cheaper",
			6 => "delivery",	   
			7 => "compare-favorite",
			8 => "sku",
			9 => "id-rate-stock",
			10 => "brand-gift",
		),
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "id-rate-stock,brand-gift,price,sku,preview,props,buttons,compare-favorite,cheaper,deals,delivery",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"LINK_IBLOCK_TYPE" => "",
		"LINK_IBLOCK_ID" => "",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_STORE" => "Y",
		"PAGER_TEMPLATE" => "master",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Товары",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"LAZY_LOAD" => "Y",
		"LOAD_ON_SCROLL" => "N",
		"TEMPLATE_AJAXID" => "catalog",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"COMPATIBLE_MODE" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "BRAND_REF",
		"USE_REVIEW" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "S",
		"USE_SHARE" => "Y",
		"SOCIAL_COUNTER" => "N",
		"SOCIAL_COPY" => "first",
		"SOCIAL_LIMIT" => "",
		"SOCIAL_SIZE" => "m",
		"DETAIL_SOCIAL_SERVICES" => array(
			0 => "facebook",
			1 => "odnoklassniki",
			2 => "telegram",
			3 => "twitter",
			4 => "vkontakte",
			5 => "",
		),
		"MESS_BTN_LAZY_LOAD" => "Показать ещё",
		"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
			0 => "ADD",
		),
		"MESS_BTN_ASK" => "Задать вопрос",
		"LINK_BTN_ASK" => "#SITE_DIR#include/forms/product_ask/?element_id=#ELEMENT_ID#",
		"CHEAPER_FORM_URL" => "#SITE_DIR#include/forms/cheaper/?element_id=#ELEMENT_ID#",
		"DETAIL_TAB_PROPERTIES" => array(
			0 => "RECOMMEND",
			1 => "HTML_VIDEO",
			2 => "DOC_FILE",
			3 => "PRICE_TABLE",
		),
		"DETAIL_TABS" => array(
			0 => "detail",
			1 => "props",
			2 => "comments",
			3 => "stock",
			4 => "prop_RECOMMEND",
			5 => "prop_HTML_VIDEO",
		),
		"DETAIL_TABS_ORDER" => "detail,props,stock,prop_HTML_VIDEO,comments",
		"MESS_DESCRIPTION_TAB" => "Описание",
		"MESS_PROPERTIES_TAB" => "Характеристики",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_VK_USE" => "Y",
		"DETAIL_VK_API_ID" => "API_ID",
		"DETAIL_FB_USE" => "Y",
		"MESS_COMMENTS_TAB" => "Комментарии",
		"DETAIL_BLOCK_LINES" => array(
			1 => "prop_DOC_FILE",
			2 => "prop_PRICE_TABLE",
		),
		"DETAIL_BLOCK_LINES_ORDER" => "prop_PRICE_TABLE,prop_DOC_FILE",
		"DETAIL_BLOCK_LINES_PROPERTIES" => array(
			0 => "DOC_FILE",
			1 => "PRICE_TABLE",
		),
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "Y",
		"DETAIL_FB_APP_ID" => "",
		"IBLOCK_ID_LANDING" => "#SYSTEM_LANDINGS_IBLOCK_ID#",
		"PROPERTY_LANDING" => array(
			0 => "BACKGROUND_IMAGE",
			1 => "PROP_BANNER_1",
			2 => "PROP_BANNER_2",
			3 => "PROP_BANNER_3",
			4 => "PROP_DESCR_1",
			5 => "PROP_PLUS_1",
			6 => "PROP_PLUS_2",
			7 => "PROP_PLUS_3",
			8 => "PROP_PLUS_4",
			9 => "PROP_REVIEW_1",
			10 => "PROP_DOC_1",
			11 => "PROP_QUEST_2",
			12 => "PROP_QUEST_3",
			13 => "PROP_SOC_SERV",
			14 => "PROP_ITEMS_1",
			15 => "PROP_GALLERY_1",
		),
		"PROPERTY_LANDING_LINK" => "LANDING_LINK",
		"SECTIONS_SHOW_SIDEBAR" => "N",
		"LIST_SHOW_SIDEBAR" => "Y",
		"DETAIL_SHOW_SIDEBAR" => "Y",
		"SORTER_ACTION_PARAM_NAME" => "alfaction",
		"SORTER_ACTION_PARAM_VALUE" => "alfavalue",
		"SORTER_CHOSE_TEMPLATES_SHOW" => "Y",
		"SORTER_CNT_TEMPLATES" => "2",
		"SORTER_CNT_TEMPLATES_0" => "Плитка",
		"SORTER_CNT_TEMPLATES_NAME_0" => "vid-2",
		"SORTER_CNT_TEMPLATES_1" => "Список",
		"SORTER_CNT_TEMPLATES_NAME_1" => "vid-1",
		"SORTER_DEFAULT_TEMPLATE" => "vid-2",
		"SORTER_SORT_BY_SHOW" => "Y",
		"SORTER_SORT_BY_NAME" => array(
			0 => "sort",
			1 => "name",
			2 => "#SORTER_CATALOG_PRICE#",
			3 => "",
		),
		"SORTER_SORT_BY_DEFAULT" => "sort_asc",
		"SORTER_OUTPUT_OF_SHOW" => "Y",
		"SORTER_OUTPUT_OF" => array(
			0 => "",
			1 => "12",
			2 => "24",
			3 => "36",
			4 => "48",
			5 => "",
		),
		"SORTER_OUTPUT_OF_DEFAULT" => "12",
		"BANNER_TYPE" => "UF_BANNER_TYPE",
		"LIST_TEMPLATE" => "master",
		"DETAIL_TEMPLATE" => "master",
		"USE_SORTER" => "Y",
		"SORTER_TEMPLATE" => "master",
		"FILTER_NAME" => "",
		"FILTER_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_PRICE_CODE" => "",
		"FILTER_SCROLL_PROPS" => array(
			0 => "BRAND_REF",
		),
		"FILTER_SEARCH_PROPS" => array(
			0 => "BRAND_REF",
		),
		"FEATURE_FILTER_USER_FIELDS" => "UF_FEATURED_FILTER",
		"FILTER_THEME" => "default",
		"HIDE_SECTION_NAME" => "N",
		"SECTIONS_COL_XS" => "6",
		"SECTIONS_COL_SM" => "6",
		"SECTIONS_COL_MD" => "6",
		"SECTIONS_COL_LG" => "6",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "L",
		"PRODUCT_DISPLAY_MODE" => "N",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
			1 => "SKU_BTN",
			2 => "SKU_DROPDOWN",
		),
		"FILTER_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"TOP_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"TOP_OFFERS_LIMIT" => "0",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "SKU_DROPDOWN",
			1 => "SKU_BTN",
			2 => "COLOR_REF",
			3 => "",
		),
		"LIST_OFFERS_LIMIT" => "0",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "SKU_DROPDOWN",
			1 => "SKU_BTN",
			2 => "COLOR_REF",
			3 => "",
		),
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => array(
		),
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "3",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Подарки к товарам этого раздела",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Подарок",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Выберите один из товаров, чтобы получить подарок",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPARE_FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"COMPARE_PROPERTY_CODE" => array(
			0 => "ARTNUMBER",
			1 => "BRAND_REF",
			2 => "HTML_VIDEO",
			3 => "ROOM_NUMBER",
			4 => "COLOR_REF",
			5 => "",
		),
		"COMPARE_OFFERS_FIELD_CODE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DETAIL_PICTURE",
			2 => "",
		),
		"COMPARE_OFFERS_PROPERTY_CODE" => array(
			0 => "SKU_DROPDOWN",
			1 => "ARTNUMBER",
			2 => "SKU_BTN",
			3 => "COLOR_REF",
			4 => "",
		),
		"COMPARE_ELEMENT_SORT_FIELD" => "sort",
		"COMPARE_ELEMENT_SORT_ORDER" => "asc",
		"DISPLAY_ELEMENT_SELECT_BOX" => "N",
		"OFFER_TREE_DROPDOWN_PROPS" => array(
			0 => "SKU_DROPDOWN",
		),
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"PRODUCT_DEALS_USER_FIELDS" => "UF_DEALS_REF",
		"PRODUCT_DEALS_PROP" => "DEALS_REF",
		"FILL_ITEM_ALL_PRICES" => "Y",
		"DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
		"OFFER_ARTNUMBER_PROP" => "ARTNUMBER",
		"BRAND_PROP" => "BRAND_REF",
		"BRAND_IBLOCK_ID" => "#SYSTEM_BRANDS_IBLOCK_ID#",
		"BRAND_IBLOCK_BRAND_PROP" => "BRAND_REF",
		"STORES" => array(
			0 => "1",
			1 => "2",
			2 => "3",
			3 => "",
		),
		"USE_MIN_AMOUNT" => "Y",
		"USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"FIELDS" => array(
			0 => "TITLE",
			1 => "ADDRESS",
			2 => "DESCRIPTION",
			3 => "PHONE",
			4 => "SCHEDULE",
			5 => "EMAIL",
			6 => "IMAGE_ID",
			7 => "COORDINATES",
			8 => "",
		),
		"MIN_AMOUNT" => "10",
		"SHOW_EMPTY_STORE" => "Y",
		"SHOW_GENERAL_STORE_INFORMATION" => "N",
		"STORE_PATH" => "#SITE_DIR#store/#store_id#",
		"MAIN_TITLE" => "Склады",
		"DETAIL_DELIVERY_PAYMENT_INFO" => "Y",
		"DETAIL_DELIVERY_LINK" => "#SITE_DIR#delivery/",
		"DETAIL_PAYMENT_LINK" => "#SITE_DIR#payment/",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"LIST_USE_PRODUCT_QUANTITY" => "N",
		"DETAIL_USE_PRODUCT_QUANTITY" => "Y",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"SHOW_MAX_QUANTITY" => "M",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие товара",
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "Y",
		"USE_SALE_BESTSELLERS" => "Y",
		"USE_BIG_DATA" => "Y",
		"BIG_DATA_RCM_TYPE" => "personal",
		"OFFER_FILTER_SCROLL_PROPS" => array(
		),
		"OFFER_FILTER_SEARCH_PROPS" => array(
		),
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"RELATIVE_QUANTITY_FACTOR" => "5",
		"MESS_RELATIVE_QUANTITY_MANY" => "много",
		"MESS_RELATIVE_QUANTITY_FEW" => "мало",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>