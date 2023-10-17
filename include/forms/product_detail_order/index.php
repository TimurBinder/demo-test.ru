<?
$IS_AJAX = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL']=='Y') ) {
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	$IS_AJAX = true;
} else {
	require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
	$APPLICATION->SetTitle("Заказать");
}
?>

<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	"product", 
	array(
		"IBLOCK_ID" => "35",
		"USE_CAPTCHA" => "Y",
		"AJAX_MODE" => "Y",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => "RS_FORM_PRODUCT_ORDER",
		"EMAIL_TO" => "beton@rosstroy.biz",
		"COMPONENT_TEMPLATE" => "product",
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"DISABLED_FIELDS" => array(
			0 => "PRODUCT",
		),
		"ITEMS_IBLOCK_ID" => "",
		"NAME_PROPERTY_CODE" => "PRODUCT",
		"FIELD_PARAMS" => "{\"162\":{\"validate\":\"None\",\"validatePattern\":\"\",\"mask\":\"\"},\"163\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"164\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"165\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"166\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"167\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>

<?if(!$IS_AJAX):?>
<?require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>
<?endif;?>
