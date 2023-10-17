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

    <?php if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
  	<div class="alert alert-success"><?=Loc::getMessage("AUTH_EMAIL_SENT")?></div>
    <?php else: ?>

    <?php if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
  	<div class="alert alert-warning"><?=Loc::getMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
    <?php endif; ?>

    <form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
        <?php if($arResult["BACKURL"] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
        <?php endif; ?>

        <input type="hidden" name="AUTH_FORM" value="Y">
    		<input type="hidden" name="TYPE" value="REGISTRATION">

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_NAME" class="control-label"><?=Loc::getMessage('AUTH_NAME');?></label>
                <input type="text" name="USER_NAME" id="INPUT_USER_NAME" maxlength="255" value="<?=$arResult["USER_NAME"]?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_LAST_NAME" class="control-label"><?=Loc::getMessage('AUTH_LAST_NAME');?></label>
                <input type="text" name="USER_LAST_NAME" id="INPUT_USER_LAST_NAME" maxlength="255" value="<?=$arResult["USER_LAST_NAME"]?>" class="form-control">
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_LOGIN" class="control-label"><?=Loc::getMessage('AUTH_LOGIN_MIN');?> <span class="required">*</span></label>
                <input type="text" name="USER_LOGIN" id="INPUT_USER_LOGIN" maxlength="255" value="<?=$arResult["USER_LOGIN"]?>" class="form-control" pattern="^.{3,}$" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_PASSWORD" class="control-label">
                    <?=Loc::getMessage('AUTH_PASSWORD_REQ');?><span class="required">*</span>
                    <?php if($arResult["SECURE_AUTH"]):?>
                        <br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
                    <?php endif; ?>
                </label>
                <input type="password" name="USER_PASSWORD" id="INPUT_USER_PASSWORD" maxlength="255" class="form-control" autocomplete="off" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_CONFIRM_PASSWORD" class="control-label">
                    <?=Loc::getMessage('AUTH_CONFIRM');?><span class="required">*</span>
                    <?php if($arResult["SECURE_AUTH"]):?>
                        <br><?=Loc::getMessage("AUTH_SECURE_NOTE")?>
                    <?php endif; ?>
                </label>
                <input type="password" name="USER_CONFIRM_PASSWORD" id="INPUT_USER_CONFIRM_PASSWORD" maxlength="255" class="form-control" autocomplete="off" required>
            </div>
        </div>

        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_EMAIL" class="control-label"><?=Loc::getMessage('AUTH_EMAIL');?><?php if($arResult["EMAIL_REQUIRED"]): ?><span class="required">*</span><?php endif; ?></label>
                <input type="text" name="USER_EMAIL" id="INPUT_USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" class="form-control"<?php if($arResult["EMAIL_REQUIRED"]): ?> required<?php endif;?>>
            </div>
        </div>

        <?php if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"): ?>
            <?php foreach ($arResult["USER_PROPERTIES"]["DATA"] as $fieldName => $arUserField): ?>
            <div class="form-group col col-md-12 field-wrap">
                <label for="INPUT_USER_LOGIN" class="control-label"><?=$arUserField["EDIT_FORM_LABEL"]?><?if ($arUserField["MANDATORY"]=="Y"):?> <span class="required">*</span><?php endif; ?></label>
                <?$APPLICATION->IncludeComponent(
                  	"bitrix:system.field.edit",
                  	$arUserField["USER_TYPE"]["USER_TYPE_ID"],
                  	array(
                    		"bVarsFromForm" => $arResult["bVarsFromForm"],
                    		"arUserField" => $arUserField,
                    		"form_name" => "bform"
                  	),
                  	null,
                  	array("HIDE_ICONS"=>"Y")
                );?>
            </div>
            <?php endforeach; ?>>
        <?php endif; ?>

        <?php if($arResult["CAPTCHA_CODE"]): ?>
        <input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
        <div class="row">
            <div class="form-group col col-md-12 field-wrap">
                <div class="captcha_wrap">
                    <div class="rsform__captcha-label"><label for="CAPTCHA_WORD"><?=Loc::getMessage('CAPTCHA_REGF_PROMT'); ?> <span class="required">*</span></label></div>
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
        
        <div class="field-wrap">
            <?$APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
                array(
                    "ID" => COption::getOptionString("main", "new_user_agreement", ""),
                    "IS_CHECKED" => "Y",
                    "AUTO_SAVE" => "N",
                    "IS_LOADED" => "Y",
                    "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
                    "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
                    "INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
                    "REPLACE" => array(
                        "button_caption" => GetMessage("AUTH_REGISTER"),
                        "fields" => array(
                            rtrim(GetMessage("AUTH_NAME"), ":"),
                            rtrim(GetMessage("AUTH_LAST_NAME"), ":"),
                            rtrim(GetMessage("AUTH_LOGIN_MIN"), ":"),
                            rtrim(GetMessage("AUTH_PASSWORD_REQ"), ":"),
                            rtrim(GetMessage("AUTH_EMAIL"), ":"),
                        )
                    ),
                )
            );?>
        </div>
        
        <div class="rsform__bottom">
            <div class="rsform__bottom-button">
                <input type="submit" class="btn btn-primary" name="Register" value="<?=Loc::getMessage('AUTH_REGISTER');?>">
            </div>
            <div class="rsform__bottom-ps">
                <p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
                <p><?=Loc::getMessage('AUTH_REQ');?></p>
                <p><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_AUTH")?></a></p>
            </div>
        </div>

    </form>

    <script type="text/javascript">
    $('form[name="bform"]').validator({focus: false});
    $('form[name="bform"]').find('input[data-inputmask]').inputmask();

    document.bform.USER_NAME.focus();
    </script>

    <?php endif; ?>
</div>
</div></div>
