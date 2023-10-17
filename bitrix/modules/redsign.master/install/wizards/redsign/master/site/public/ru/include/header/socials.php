<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"socials_head", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "#SITE_DIR#include/empty.php",
		"SOCIAL_ICONS" => "[[\"https://vk.com/\",\"vkontakte\"],[\"https://www.facebook.com/\",\"facebook\"],[\"https://twitter.com/\",\"twitter\"],[\"https://www.instagram.com/\",\"instagram\"]]",
		"EDIT_TEMPLATE" => ""
	),
	false
); ?>