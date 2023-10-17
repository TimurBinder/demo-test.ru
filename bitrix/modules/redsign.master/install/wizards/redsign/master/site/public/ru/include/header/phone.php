<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"phone", 
	array(
		"COMPONENT_TEMPLATE" => "phone",
		"AREA_FILE_SHOW" => "file",
		"PATH" => "#SITE_DIR#include/empty.php",
		"PHONES" => array(
			0 => "#SALE_PHONE#",
		),
		"EDIT_TEMPLATE" => ""
	),
	false
); ?>