<?php $arProp = $arItem["DISPLAY_PROPERTIES"];?>

<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"ask_question", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/template/ask_question_landing.php",
		"BLOCK_TYPE" => "wide",
		"BTN_LINK" => "/include/forms/product_ask/?element_id=#ELEMENT_ID#",
		"URL_PARAMS" => array(
			"#ELEMENT_ID#" => $arResult["ELEMENT"]["ID"],
		),
		"BTN_TEXT" => $arProp["PROP_QUEST_3"]["DISPLAY_VALUE"],
		"TITLE_TEXT" => $arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"],
		"DESCRIPTION_TEXT" => $arProp["PROP_QUEST_2"]["DISPLAY_VALUE"],
		"EDIT_TEMPLATE" => "",
		"COMPONENT_TEMPLATE" => "ask_question"
	),
	false
);?>
