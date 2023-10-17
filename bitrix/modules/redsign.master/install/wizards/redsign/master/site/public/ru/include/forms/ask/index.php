<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Задать вопрос");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"popup", 
	array(
		"IBLOCK_ID" => "#FORMS_ASK_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_ASK",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"COMPONENT_TEMPLATE" => "popup",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FIELD_PARAMS" => "{\"30\":{\"validate\":\"pattern\",\"validatePattern\":\"^.{3,}\$\",\"mask\":\"\"},\"31\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"32\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"33\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+7 (999) 999-99-99\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>