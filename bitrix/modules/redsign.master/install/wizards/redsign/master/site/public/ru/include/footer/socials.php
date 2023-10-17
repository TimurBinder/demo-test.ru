<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"socials_foot", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "#SITE_DIR#include/empty.php",
		"SOCIAL_ICONS" => "[[\"https://www.facebook.com/\",\"facebook\",\"#3b5998\"],[\"https://plus.google.com\",\"google-plus\",\"#d73d32\"],[\"https://ok.ru/\",\"ok\",\"#f2720c\"],[\"https://twitter.com/\",\"twitter\",\"#1da1f2\"],[\"https://vk.com/\",\"vkontakte\",\"#507299\"]]",
		"EDIT_TEMPLATE" => "",
		"COMPONENT_TEMPLATE" => "socials_foot"
	),
	false
); ?>