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
			4 => "COMMENT",
			5 => "",
		),
		"IBLOCK_TYPE" => "lightbasket",
		"IBLOCK_ID" => "28",
		"ITEMS_PROP" => "ORDER_LIST",
		"BASKET_IBLOCK_TYPE" => "catalog",
		"BASKET_IBLOCK_ID" => "",
		"BASKET_PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"CATALOG_IBLOCK_TYPE" => "catalog",
		"CATALOG_IBLOCK_ID" => "3",
		"CATALOG_PROPS" => array(
			0 => "MEASURE",
			1 => "CML2_ARTICLE",
			2 => "",
		),
		"PATH_TO_CART" => "/cart/",
		"SHOW_CONFIRM" => "N",
		"CONFIRM_TEXT" => "Соглашаюсь на обработку <a href=\"/about/licence_work/\">персональных данных</a>",
		"FIELD_PARAMS" => "{\"112\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"113\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"114\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"115\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"116\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"117\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
		"RS_VK_ID" => "",
		"RS_FB_PAGE" => "",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
