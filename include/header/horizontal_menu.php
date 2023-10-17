<?$APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"horizontal", 
	array(
		"ROOT_MENU_TYPE" => "main",
		"MAX_LEVEL" => "4",
		"CHILD_MENU_TYPE" => "mainsub",
		"USE_EXT" => "Y",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"MENU_TYPE" => "AIM",
		"COMPONENT_TEMPLATE" => "horizontal",
		"IBLOCKS_MENU_ITEMS" => array(
			0 => "#CATALOG_CATALOG_IBLOCK_ID#",
		)
	),
	false
); ?>