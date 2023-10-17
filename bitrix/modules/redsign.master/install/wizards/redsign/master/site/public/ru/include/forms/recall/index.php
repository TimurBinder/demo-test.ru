<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратный звонок");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms",
	"popup",
	array(
		"IBLOCK_ID" => "#FORMS_FORM_RECALL_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_RECALL",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"COMPONENT_TEMPLATE" => "popup",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FIELD_PARAMS" => "{\"38\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"39\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+7 (999) 999-99-99\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_B24_CRM_FORM" => "N",
		"B24_CRM_FORM_ID" => "3",
		"B24_CRM_FORM_SEC" => "cl5lo1"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
