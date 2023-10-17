<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="row"><div class="col-sm-9 col-md-7">
<div class="rsform">
    <?php
    if(!empty($arParams["~AUTH_RESULT"])):
     $text = str_replace(array("<br>", "<br />"), "\n", $arParams["~AUTH_RESULT"]["MESSAGE"]);
    ?>
    <div class="alert <?=($arParams["~AUTH_RESULT"]["TYPE"] == "OK"? "alert-success":"alert-danger")?>"><?=nl2br(htmlspecialcharsbx($text))?></div>
    <?php endif; ?>

    <form method="post" action="<?=$arResult["AUTH_FORM"]?>" name="bform">
        <?php if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
        <?php endif; ?>

        <input type="hidden" name="AUTH_FORM" value="Y">
    		<input type="hidden" name="TYPE" value="CHANGE_PWD">

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_LOGIN" class="control-label"><?=Loc::getMessage('AUTH_LOGIN');?> </label>
                <input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_CHECKWORD" class="control-label"><?=Loc::getMessage('AUTH_CHECKWORD');?> </label>
                <input type="text" name="USER_CHECKWORD" id="USER_CHECKWORD" maxlength="255" value="<?=$arResult["USER_CHECKWORD"]?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_PASSWORD" class="control-label">
                    <?=Loc::getMessage('AUTH_NEW_PASSWORD_REQ');?>
                    <?php if($arResult["SECURE_AUTH"]):?>
                        <br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
                    <?php endif; ?>
                </label>
                <input type="password" name="USER_PASSWORD" id="USER_PASSWORD" maxlength="255" class="form-control" autocomplete="off">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_CONFIRM_PASSWORD" class="control-label">
                    <?=Loc::getMessage('AUTH_NEW_PASSWORD_CONFIRM');?>
                    <?php if($arResult["SECURE_AUTH"]):?>
                        <br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
                    <?php endif; ?>
                </label>
                <input type="password" name="USER_CONFIRM_PASSWORD" id="USER_CONFIRM_PASSWORD" maxlength="255" class="form-control" autocomplete="off">
            </div>
        </div>

        <?php if($arResult["CAPTCHA_CODE"]): ?>
        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <div class="captcha_wrap">
                    <div class="rsform__captcha-label"><label for="CAPTCHA_WORD"><?=Loc::getMessage('system_auth_captcha'); ?> </label></div>
                    <div class="row">
                        <div class="rsform__captcha-input col col-md-6 col-xs-12">
                            <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                            <input class="form-control req-input form-item" type="text" name="captcha_word" id="CAPTCHA_WORD" size="30" maxlength="50" value="" required>
                        </div>
                        <div class="rsform__captcha-image col col-md-6 col-xs-12">
                            <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="rsform__bottom">
            <div class="rsform__bottom-button">
                <input type="submit" class="btn btn-primary" name="change_pwd" value="<?=Loc::getMessage('AUTH_CHANGE');?>">
            </div>
            <div class="rsform__bottom-ps">
                <p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
                <p><?=Loc::getMessage('AUTH_REQ');?></p>
                <p><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_AUTH")?></a></p>
            </div>
        </div>

    </form>
</div>
</div></div>
<script>
document.bform.USER_LOGIN.focus();
</script>
