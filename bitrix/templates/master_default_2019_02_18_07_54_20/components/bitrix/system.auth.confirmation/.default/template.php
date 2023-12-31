<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

//one css for all system.auth.* forms
//here you can place your own messages
switch($arResult["MESSAGE_CODE"])
{
	case "E01":
		//When user not found
		$class = "alert-warning";
		break;
	case "E02":
		//User was successfully authorized after confirmation
		$class = "alert-success";
		break;
	case "E03":
		//User already confirm his registration
		$class = "alert-warning";
		break;
	case "E04":
		//Missed confirmation code
		$class = "alert-warning";
		break;
	case "E05":
		//Confirmation code provided does not match stored one
		$class = "alert-danger";
		break;
	case "E06":
		//Confirmation was successfull
		$class = "alert-success";
		break;
	case "E07":
		//Some error occured during confirmation
		$class = "alert-danger";
		break;
	default:
		$class = "alert-warning";
}
?>

<?php
if($arResult["MESSAGE_TEXT"] <> ''):
	  $text = str_replace(array("<br>", "<br />"), "\n", $arResult["MESSAGE_TEXT"]);
?>
<div class="alert <?=$class?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
<?php endif; ?>

<?php if($arResult["SHOW_FORM"]): ?>
<div class="rsform">
    <form method="post" action="<?=$arResult["FORM_ACTION"]?>">
        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_LOGIN" class="control-label"><?=Loc::getMessage('CT_BSAC_LOGIN');?> </label>
                <input type="text" name="<?=$arParams["LOGIN"]?>" id="USER_LOGIN" maxlength="50" value="<?=$arResult["LOGIN"]?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="CONFIRM_CODE" class="control-label"><?=Loc::getMessage('CT_BSAC_CONFIRM_CODE');?> </label>
                <input type="text" name="<?=$arParams["CONFIRM_CODE"]?>" id="CONFIRM_CODE" maxlength="50" value="<?=$arResult["CONFIRM_CODE"]?>" class="form-control">
            </div>
        </div>

        <input type="hidden" name="<?=$arParams["USER_ID"]?>" value="<?=$arResult["USER_ID"]?>">

        <div class="rsform__bottom">
            <div class="rsform__bottom-button">
                <input type="submit" class="btn btn-primary" value="<?=Loc::getMessage('CT_BSAC_CONFIRM');?>">
            </div>
        </div>
    </form>
</div>
<?php else: ?>
    <?php $APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "", array());?>
<?php endif; ?>
