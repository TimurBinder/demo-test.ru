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
    <div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
    <?php endif; ?>

    <?php
    if($arResult['ERROR_MESSAGE'] <> ''):
        $text = str_replace(array("<br>", "<br />"), "\n", $arResult['ERROR_MESSAGE']);
    ?>
    <div class="alert alert-danger"><?=nl2br(htmlspecialcharsbx($text))?></div>
    <?php endif; ?>

    <h4><?=Loc::getMessage('AUTH_PLEASE_AUTH');?></h4>
    <?php
    if($arResult["AUTH_SERVICES"]) {
        $APPLICATION->IncludeComponent("bitrix:socserv.auth.form",
            "flat",
            array(
                "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                "AUTH_URL" => $arResult["AUTH_URL"],
                "POST" => $arResult["POST"],
            ),
            $component,
            array("HIDE_ICONS"=>"Y")
        );
    }
    ?>
    <form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
        <input type="hidden" name="AUTH_FORM" value="Y">
        <input type="hidden" name="TYPE" value="AUTH">

        <?php if (strlen($arResult["BACKURL"]) > 0):?>
    		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
        <?php endif; ?>

        <?php foreach ($arResult["POST"] as $key => $value):?>
    		<input type="hidden" name="<?=$key?>" value="<?=$value?>">
        <?php endforeach?>

        <div class="rsform__all-field clearfix">
            <div class="row">
                <div class="form-group col col-md-12 field-wrap">
                    <label for="INPUT_USER_LOGIN" class="control-label"><?=Loc::getMessage('AUTH_LOGIN');?><span class="required">*</span></label>
                    <input type="text" name="USER_LOGIN" id="INPUT_USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="form-group col col-md-12 field-wrap">
                    <label for="INPUT_USER_PASSWORD" class="control-label">
                        <?=Loc::getMessage('AUTH_PASSWORD');?><span class="required">*</span>
                        <?php if($arResult["SECURE_AUTH"]):?>
                            <?=Loc::getMessage("AUTH_SECURE_NOTE")?>
                        <?php endif; ?>
                    </label>
                    <input type="password" name="USER_PASSWORD" id="INPUT_USER_PASSWORD" maxlength="255" class="form-control" autocomplete="off" required>
                </div>
            </div>

            <?php if($arResult["CAPTCHA_CODE"]): ?>
            <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
            <div class="row">
                <div class="form-group col col-md-12 field-wrap">
                    <div class="captcha_wrap">
                       <div class="rsform__captcha-label"><label for="CAPTCHA_CODE"><?=Loc::getMessage('AUTH_CAPTCHA_PROMT'); ?> <span class="required">*</span></label></div>
                       <div class="row">
                          <div class="rsform__captcha-input col col-md-6 col-xs-12">
                              <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                              <input class="form-control req-input form-item" type="text" name="captcha_word" id="CAPTCHA_CODE" size="30" maxlength="50" value="" required>
                          </div>
                          <div class="rsform__captcha-image col col-md-6 col-xs-12">
                              <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
                          </div>
                       </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($arResult["STORE_PASSWORD"] == "Y"):?>
            <div class="row">
                <div class="form-group col col-md-12 field-wrap">

                    <input type="checkbox" class="custom-checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y">
                    <label for="USER_REMEMBER"><?=Loc::getMessage('AUTH_REMEMBER_ME');?></label>
                </div>
            </div>
            <?php endif; ?>

            <div class="rsform__bottom">
                <div class="rsform__bottom-button">
                    <input type="submit" class="btn btn-primary" name="Login" value="<?=Loc::getMessage('AUTH_AUTHORIZE');?>">
                </div>
                <div class="rsform__bottom-ps"><?=Loc::getMessage('AUTH_FORM_NOTE');?></div>
            </div>
        </div>
    </form>
    <hr>
    <?php if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
    <noindex><p><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a></p></noindex>
    <?php endif; ?>

    <?php if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
    <noindex>
        <p>	<span style="color: #676d72;font-size: 12px;"><?=Loc::getMessage("AUTH_FIRST_ONE")?></span><br /><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_REGISTER")?></a></p>
    </noindex>
    <?php endif; ?>
</div>
</div></div>
<script type="text/javascript">
    $('form[name="form_auth"]').validator({focus: false});
    $('form[name="form_auth"]').find('input[data-inputmask]').inputmask();

    <?php if (strlen($arResult["LAST_LOGIN"])>0): ?>
    try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
    <?php else: ?>
    try{document.form_auth.USER_LOGIN.focus();}catch(e){}
    <?php endif ?>
</script>
