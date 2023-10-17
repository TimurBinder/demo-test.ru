<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Ваша корзина");
?><?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.basket", 
	"master", 
	array(
		"COMPONENT_TEMPLATE" => "master",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "3",
		"PROPS" => array(
			0 => "MEASURE",
			1 => "CML2_ARTICLE",
			2 => "",
		),
		"PATH_TO_ORDER" => "/cart/order/",
		"AJAX_MODE" => "N",
		"COMPOSITE_FRAME_MODE" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
