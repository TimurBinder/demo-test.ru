<?php
    if ($arProp["PROP_FORM_1"]["VALUE"] != "")
        $iblock = $arProp["PROP_FORM_1"]["VALUE"];
        
    if ($arProp["PROP_FORM_2"]["VALUE"] != "")
        $eventType = $arProp["PROP_FORM_2"]["VALUE"];
    else
        $eventType = "RS_FORM_FAQ";
?>
<div class="landing__container-small landing__form" id="form_<?=$arItem['ID']?>">
<?php
$IS_AJAX = false;
if( isset($_SERVER['HTTP_X_REQUESTED_WITH']) || (isset($_REQUEST['AJAX_CALL']) && $_REQUEST['AJAX_CALL']=='Y') && $_REQUEST["FORM_".$arItem["ID"] == "Y"] ) {
	$APPLICATION->RestartBuffer();
	$IS_AJAX = true;
}
$templates = "popup";
if ($arProp["PROP_FORM_3"]["VALUE"] != "")
	$templates = $arProp["PROP_FORM_3"]["VALUE_XML_ID"];
?>

<?$APPLICATION->IncludeComponent(
	"redsign:forms", 
	$templates, 
	array(
		"IBLOCK_ID" => $iblock,
		"USE_CAPTCHA" => "N",
		"AJAX_MODE" => "N",
		"SUCCESS_MESSAGE" => "Cпасибо, ваша заявка принята!",
		"EVENT_TYPE" => $eventType,
		"EMAIL_TO" => "s.lukinin@redsign.ru",
		"COMPONENT_TEMPLATE" => $templates,
		"IBLOCK_TYPE" => "forms",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "N",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"FIELD_PARAMS" => "{\"30\":{\"validate\":\"pattern\",\"validatePattern\":\"^.{3,}\$\",\"mask\":\"\"},\"31\":{\"validate\":\"email\",\"validatePattern\":\"\",\"mask\":\"\"},\"32\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"\"},\"33\":{\"validate\":\"\",\"validatePattern\":\"\",\"mask\":\"+7 (999) 999-99-99\"}}",
		"USER_CONSENT" => "Y",
		"USER_CONSENT_ID" => "1",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"FORM_ID" => $arItem["ID"],
		"USER_CONSENT_IS_LOADED" => "N"
	),
	false
);?>

<?if($IS_AJAX):?>
<?die();?>
<?endif;?>
</div>
