<?$APPLICATION->IncludeComponent(
	"redsign:lightbasket.basket", 
	"flying", 
	array(
		"COMPONENT_TEMPLATE" => "flying",
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "3",
		"PROPS" => array(
			0 => "MEASURE",
			1 => "CML2_ARTICLE",
			2 => "",
		),
		"PATH_TO_ORDER" => "/cart/order/",
		"AJAX_MODE" => "N",
		"PATH_TO_CART" => "/cart/",
		"PATH_TO_CATALOG" => "/catalog/",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>