<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ваша корзина");
?><?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.basket", 
	"master", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_CATALOG_IBLOCK_ID#",
		"PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PATH_TO_ORDER" => "#SITE_DIR#cart/order/",
		"AJAX_MODE" => "N",
		"COMPOSITE_FRAME_MODE" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
