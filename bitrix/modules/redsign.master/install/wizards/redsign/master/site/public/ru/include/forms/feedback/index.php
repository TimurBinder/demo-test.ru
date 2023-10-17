<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Обратная связь");
?>
<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"popup", 
	array(
		"IBLOCK_ID" => "#FORMS_FEEDBACK_IBLOCK_ID#",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_FEEDBACK",
		"EMAIL_TO" => "#SHOP_EMAIL#",
		"COMPONENT_TEMPLATE" => "popup",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"DISABLED_FIELDS" => array(
			0 => "PRODUCT",
		),
		"ITEMS_IBLOCK_ID" => "1",
		"NAME_PROPERTY_CODE" => "PRODUCT",
		"FIELD_PARAMS" => "{\"118\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\",\"placeholder\":\"\"},\"119\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\",\"placeholder\":\"\"},\"120\":{\"validate\":\"url\",\"validatePattern\":\"\",\"mask\":\"\",\"placeholder\":\"http://site.ru\"},\"121\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\",\"placeholder\":\"\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "#USER_CONSENT_ID#",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_B24_CRM_FORM" => "N"
	),
	false
);?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>