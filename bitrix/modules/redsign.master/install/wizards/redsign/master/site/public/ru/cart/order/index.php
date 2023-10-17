<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформление заказа");
$APPLICATION->SetPageProperty("wide_page", "Y");
$APPLICATION->SetPageProperty("hide_page", "Y");
?><?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.order", 
	"master", 
	array(
		"COMPONENT_TEMPLATE" => "master",
		"FIELDS_PROPS" => array(
			0 => "NAME",
			1 => "PHONE_NUMBER",
			2 => "EMAIL",
			3 => "COMPANY_NAME",
			4 => "ADRESS",
			5 => "COMMENT",
			6 => "DELIVERY_SERVICE",
			7 => "",
		),
		"IBLOCK_TYPE" => "lightbasket",
		"IBLOCK_ID" => "#LIGHTBASKET_ORDERS_IBLOCK_ID#",
		"ITEMS_PROP" => "ORDER_LIST",
		"BASKET_IBLOCK_TYPE" => "catalog",
		"BASKET_IBLOCK_ID" => "",
		"BASKET_PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"CATALOG_IBLOCK_TYPE" => "catalog",
		"CATALOG_IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"CATALOG_PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PATH_TO_CART" => "#SITE_DIR#cart/",
		"SHOW_CONFIRM" => "Y",
		"CONFIRM_TEXT" => "Соглашаюсь на обработку <a href=\"#SITE_DIR#about/licence_work/\">персональных данных</a>",
		"FIELD_PARAMS" => "{\"48\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"49\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+7 (999) 99-99-99\"},\"50\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"51\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"52\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"54\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
		"RS_VK_ID" => "20003922",
		"RS_FB_PAGE" => "https://www.facebook.com/redsignRU/",
        "USER_CONSENT_ID" => "#USER_CONSENT_ID#"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
