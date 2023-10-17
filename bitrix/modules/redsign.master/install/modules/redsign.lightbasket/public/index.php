<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
?><?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.basket",
	".default",
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "",
		"PROPS" => array(
			0 => "CML2_ARTICLE",
			1 => "",
		),
		"PATH_TO_ORDER" => "/cart/order/",
		"AJAX_MODE" => "Y",
		"COMPOSITE_FRAME_MODE" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
