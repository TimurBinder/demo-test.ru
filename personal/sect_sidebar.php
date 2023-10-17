<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "sidebar",
    array(
        "ROOT_MENU_TYPE" => "mainsub",
        "CHILD_MENU_TYPE" => "mainsub",
        "MAX_LEVEL" => "3",
        "USE_EXT" => "Y",
        "DELAY" => "N",
        "ALLOW_MULTI_SELECT" => "N",
        "MENU_CACHE_TYPE" => "A",
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "MENU_CACHE_GET_VARS" => "",
    )
);?>
<?php $APPLICATION->ShowViewContent('site_sidebar'); ?>
<?$APPLICATION->IncludeComponent("bitrix:main.include", "sidebar_buttons", array(
	"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "/include/forms/ask/",
		"NAME_SIDE_SVG" => "message",
		"PATH" => "/include/sidebar/button_inc1.php"
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_buttons",
	Array(
		"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "N",
		"LINK_SIDE_WIDGET" => "/contacts/",
		"NAME_SIDE_SVG" => "location",
		"PATH" => "/include/sidebar/button_inc2.php"
	)
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"sidebar_buttons", 
	array(
		"AREA_FILE_SHOW" => "file",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "/include/forms/feedback/",
		"NAME_SIDE_SVG" => "pencil",
		"PATH" => "/include/sidebar/button_inc3.php",
		"COMPONENT_TEMPLATE" => "sidebar_buttons"
	),
	false
);?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.include",
	"sidebar_buttons",
	Array(
		"AREA_FILE_SHOW" => "file",
		"COMPONENT_TEMPLATE" => "sidebar_buttons",
		"EDIT_TEMPLATE" => "",
		"IS_LINK_POPUP" => "Y",
		"LINK_SIDE_WIDGET" => "/include/forms/recall/",
		"NAME_SIDE_SVG" => "device-mobile",
		"PATH" => "/include/sidebar/button_inc4.php"
	)
);?>