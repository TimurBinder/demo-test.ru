<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.section",
	"master",
	array(
		"COMPONENT_TEMPLATE" => "master",
		"ACTION_VARIABLE" => "action",
		"ADD_CONTAINER" => "N",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "#SITE_DIR##CART_PATH#",
		"BLOCK_NAME_IS_LINK" => "N",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPATIBLE_MODE" => "N",
		"CURRENCY_PROP" => "CURRENCY",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISCOUNT_PROP" => "DISCOUNT",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "Y",
		"COMPARE_PATH" => "",
		"MESS_BTN_COMPARE" => "Сравнить",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilter",
		"IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"IS_USE_CART" => "Y",
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
		"LABEL_PROP_POSITION" => "bottom-left",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "0",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "master",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "4",
		"PARENT_NAME" => "Может вы что-то забыли?",
		"PARTIAL_PRODUCT_PROPERTIES" => "Y",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"PRICE_DECIMALS_PROP" => "0",
		"PRICE_PROP" => "PRICE",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS" => array(
			0 => "price",
			1 => "quantity",
			2 => "buttons",
			3 => "preview",
		),
		"PRODUCT_BLOCKS_ORDER" => "preview,price,quantity,buttons",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
		"PROPERTY_CODE" => array(
			0 => "NEWPRODUCT",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"PROP_CURRENCY" => "CURRENCY",
		"PROP_PRICE" => "PRICE",
		"PROP_PRICE_DECIMALS" => "0",
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => "",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PARENT_NAME" => "Y",
		"SHOW_PRICE_COUNT" => "1",
		"USE_ENHANCED_ECOMMERCE" => "Y",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRICE_DECIMALS" => "0",
		"DATA_LAYER_NAME" => "dataLayer",
		"BRAND_PROPERTY" => "BRAND_REF",
		"CUSTOM_FILTER" => "",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_MAX_QUANTITY" => "M",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"RELATIVE_QUANTITY_FACTOR" => "5",
		"MESS_RELATIVE_QUANTITY_MANY" => "много",
		"MESS_RELATIVE_QUANTITY_FEW" => "мало",
		"CONVERT_CURRENCY" => "Y",
		"CURRENCY_ID" => "RUB",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"ADD_TO_BASKET_ACTION" => "ADD",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
			0 => "COLOR_REF",
		),
	),
	false
);?>