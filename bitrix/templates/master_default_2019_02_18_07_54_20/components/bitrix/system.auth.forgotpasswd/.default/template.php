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

    <form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
        <?php if (strlen($arResult["BACKURL"]) > 0): ?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
        <?php endif; ?>

        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="SEND_PWD">

        <p><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_1")?></p>
        <p><b><?=Loc::getMessage('AUTH_GET_CHECK_STRING')?></b></p>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_LOGIN" class="control-label"><?=Loc::getMessage('AUTH_LOGIN');?> </label>
                <input type="text" name="USER_LOGIN" id="USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" class="form-control">
            </div>
        </div>
        <p><?=Loc::getMessage('AUTH_OR');?></p>
        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="USER_EMAIL" class="control-label"><?=Loc::getMessage('AUTH_EMAIL');?> </label>
                <input type="text" name="USER_EMAIL" id="USER_EMAIL" maxlength="255" class="form-control">
            </div>
        </div>

        <?php if($arResult["CAPTCHA_CODE"]): ?>
        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <div class="captcha_wrap">
                    <div class="rsform__captcha-label"><label for="CAPTCHA_WORD"><?=Loc::getMessage('system_auth_captcha'); ?></label></div>
                    <div class="row">
                        <div class="rsform__captcha-input col col-md-6 col-xs-12">
                            <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                            <input class="form-control req-input form-item" type="text" name="captcha_word" id="CAPTCHA_WORD" size="30" maxlength="50" value="">
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
                <input type="submit" class="btn btn-primary" name="send_account_info" value="<?=Loc::getMessage('AUTH_SEND');?>">
            </div>
            <div class="rsform__bottom-ps">
                <p><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=Loc::getMessage("AUTH_AUTH")?>  </a></p>
            </div>
        </div>
    </form>
</div>
</div></div>
