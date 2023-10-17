<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"phone", 
	array(
		"COMPONENT_TEMPLATE" => "phone",
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/empty.php",
		"PHONES" => array(
			0 => "",
			1 => "+7(4242) 73-33-33",
			2 => "",
		),
		"EDIT_TEMPLATE" => ""
	),
	false
); ?>