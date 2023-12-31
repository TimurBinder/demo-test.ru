<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск");
?><?$APPLICATION->IncludeComponent(
	"bitrix:search.page",
	"master",
	Array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CATALOG_CURRENCY_PROP" => "CURRENCY",
		"CATALOG_DISCOUNT_PROP" => "DISCOUNT",
		"CATALOG_IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"CATALOG_IBLOCK_TYPE" => "catalog",
		"CATALOG_IS_USE_CART" => "Y",
		"CATALOG_MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"CATALOG_MESS_BTN_BUY" => "Купить",
		"CATALOG_MESS_BTN_DETAIL" => "Подробнее",
		"CATALOG_MESS_BTN_SUBSCRIBE" => "Подписаться",
		"CATALOG_MESS_NOT_AVAILABLE" => "Нет в наличии",
		"CATALOG_PRICE_PROP" => "PRICE",
		"CHECK_DATES" => "N",
		"COLOR_NEW" => "000000",
		"COLOR_OLD" => "C8C8C8",
		"COLOR_TYPE" => "Y",
		"COMPONENT_TEMPLATE" => "master",
		"DEFAULT_SORT" => "rank",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"FILTER_NAME" => "",
		"FONT_MAX" => "50",
		"FONT_MIN" => "10",
		"IS_USE_CART" => "Y",
		"NAME_TEMPLATE" => "",
		"NO_WORD_LOGIC" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "master",
		"PAGER_TITLE" => "Результаты поиска",
		"PAGE_RESULT_COUNT" => "10",
		"PERIOD_NEW_TAGS" => "",
		"RESTART" => "Y",
		"SHOW_CHAIN" => "Y",
		"SHOW_ITEM_DATE_CHANGE" => "Y",
		"SHOW_ITEM_TAGS" => "Y",
		"SHOW_LOGIN" => "Y",
		"SHOW_ORDER_BY" => "Y",
		"SHOW_TAGS_CLOUD" => "N",
		"SHOW_WHEN" => "Y",
		"SHOW_WHERE" => "Y",
		"STRUCTURE_FILTER" => "structure",
		"TAGS_INHERIT" => "Y",
		"TAGS_PAGE_ELEMENTS" => "150",
		"TAGS_PERIOD" => "",
		"TAGS_SORT" => "NAME",
		"TAGS_URL_SEARCH" => "",
		"USE_LANGUAGE_GUESS" => "Y",
		"USE_SUGGEST" => "Y",
		"USE_TITLE_RANK" => "Y",
		"WIDTH" => "100%",
		"arrFILTER" => array(
			0 => "no",
		),
		"arrWHERE" => array(
			0 => "iblock_catalog",
			1 => "iblock_helpinfo",
		),
		"CATALOG_ADD_PICT_PROP" => "MORE_PHOTO",
		"CATALOG_LABEL_PROP" => array(
		),
		"OFFER_ADD_PICT_PROP" => "-",
		"CATALOG_PRODUCT_SUBSCRIPTION" => "Y",
		"CATALOG_SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"ARTNUMBER_PROP" => "-",
		"MESS_BTN_ADD_TO_BASKET" => "В корзину",
		"MESS_BTN_COMPARE" => "Сравнение",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"CATALOG_PRODUCT_BLOCKS" => array(
			0 => "price",
			1 => "buttons",
		),
		"CATALOG_PRODUCT_BLOCKS_ORDER" => "price,buttons",
		"FILL_ITEM_ALL_PRICES" => "N",
		"PRICE_CODE" => array(
			0 => "BASE",
			1 => "OPT",
			2 => "VIP",
		),
		"PRICE_VAT_INCLUDE" => "N",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"CONVERT_CURRENCY" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"PRODUCT_QUANTITY_VARIABLE" => "",
		"CATALOG_ADD_TO_BASKET_ACTION" => "ADD",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>