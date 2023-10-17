<?$APPLICATION->IncludeComponent(
	"bitrix:main.include", 
	"ask_question", 
	array(
		"AREA_FILE_SHOW" => "file",
		"PATH" => "/include/template/ask_question.php",
		"BLOCK_TYPE" => "wide",
		"BTN_LINK" => "/include/forms/product_ask/?element_id=#ELEMENT_ID#",
		"URL_PARAMS" => array(
			"#ELEMENT_ID#" => $elementId,
		),
		"BTN_TEXT" => "Задать вопрос",
		"EDIT_TEMPLATE" => "",
		"COMPONENT_TEMPLATE" => "ask_question"
	),
	false
);?>