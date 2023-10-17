<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.order",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"FIELDS_PROPS" => array(
			0 => "NAME",
			1 => "PHONE_NUMBER",
			2 => "COMPANY_NAME",
			3 => "EMAIL",
			4 => "ADRESS",
			5 => "COMMENT",
			6 => "DELIVERY_SERVICE",
			7 => "",
		),
		"IBLOCK_TYPE" => "lightbasket",
    "IBLOCK_ID" => "",
		"ITEMS_PROP" => "ORDER_LIST",
		"BASKET_IBLOCK_TYPE" => "catalog",
		"BASKET_IBLOCK_ID" => "",
		"BASKET_PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"CATALOG_IBLOCK_TYPE" => "catalog",
		"CATALOG_IBLOCK_ID" => "",
		"CATALOG_PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PATH_TO_CART" => "/cart/"
	),
	false
);?><br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
