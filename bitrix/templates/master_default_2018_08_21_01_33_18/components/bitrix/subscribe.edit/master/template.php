<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

?>
<div class="b-subscription">
    <?php foreach ($arResult["MESSAGE"] as $itemID => $itemValue): ?>
        <?=ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));?>
    <?php endforeach; ?>
    <?php foreach ($arResult["ERROR"] as $itemID => $itemValue): ?>
        <?=ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR")); ?>
    <?php endforeach; ?>

    <?php if ($arResult["ALLOW_ANONYMOUS"]=="N" && !$USER->IsAuthorized()): ?>
        <?=ShowMessage(array("MESSAGE"=>Loc::getMessage("CT_BSE_AUTH_ERR"), "TYPE"=>"ERROR"));?>
    <?php else: ?>
        <div class="row">
            <div class="col col-md-6">
                <form action="<?=$arResult["FORM_ACTION"]?>" method="post">
                    <?=bitrix_sessid_post();?>
                    <input type="hidden" name="PostAction" value="<?=($arResult["ID"]>0? "Update":"Add")?>">
                    <input type="hidden" name="ID" value="<?=$arResult["SUBSCRIPTION"]["ID"];?>">
                    <input type="hidden" name="RUB_ID[]" value="0">

                    <div class="field-wrap">
                        <div class="label-wrap"><?=Loc::getMessage('RS.FIELD_EMAIL')?><span class="required"> *</span></div>
                        <input class="form-control" type="text" name="EMAIL" value="<?=$arResult["SUBSCRIPTION"]["EMAIL"]!=""? $arResult["SUBSCRIPTION"]["EMAIL"]: $arResult["REQUEST"]["EMAIL"];?>">
                    </div>

                    <div class="field-wrap">
                        <div class="label-wrap"><?=Loc::getMessage('CT_BSE_FORMAT_LABEL')?></div>
                        <div class="b-subscription__padleft">
                            <div class="row">
                                <div class="col col-xs-12 col-sm-6 col-md-3">
                                    <input class="custom-radio"  type="radio" name="FORMAT" id="MAIL_TYPE_TEXT" value="text" <?php if ($arResult["SUBSCRIPTION"]["FORMAT"] != "html"): echo "checked"; endif; ?>><label for="MAIL_TYPE_TEXT"><?=Loc::getMessage("CT_BSE_FORMAT_TEXT")?></label>
                                </div>
                                <div class="col col-xs-12 col-sm-6 col-md-3">
                                    <input class="custom-radio" type="radio" name="FORMAT" id="MAIL_TYPE_HTML" value="html" <?php if ($arResult["SUBSCRIPTION"]["FORMAT"] == "html"): echo "checked"; endif; ?>><label for="MAIL_TYPE_HTML"><?=Loc::getMessage("CT_BSE_FORMAT_HTML")?></label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="field-wrap">
                        <div class="label-wrap"><?=Loc::getMessage('RS.RUBRIC')?></div>
                        <div class="b-subscription__padleft">
                            <?php foreach ($arResult["RUBRICS"] as $itemID => $itemValue): ?>
                                <div class="b-subscription__rubric">
                                    <input type="checkbox" class="custom-checkbox" id="RUBRIC_<?=$itemID?>" name="RUB_ID[]" value="<?=$itemValue["ID"]?>"<?php if ($itemValue["CHECKED"]): echo " checked"; endif; ?>>
                                    <label for="RUBRIC_<?=$itemID?>">
                                        <?=$itemValue["NAME"]?>
                                        <div class="b-subscription__rubric-desc"><small><?=$itemValue["DESCRIPTION"]?></small></div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <hr>

                    <div class="field-wrap">
                        <?php if ($arResult["ID"]==0): ?>
                            <div class="b-subscription__note"><?=Loc::getMessage("CT_BSE_NEW_NOTE")?></div>
                        <?php else: ?>
                            <div class="b-subscription__note"><?=Loc::getMessage("CT_BSE_EXIST_NOTE")?></div>
                        <?php endif; ?>
                        <div><br>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:main.userconsent.request",
                            "form",
                            array(
                                "ID" => $arParams['USER_CONSENT_ID'],
                                "IS_CHECKED" => 'Y',
                                "AUTO_SAVE" => "Y",
                                "IS_LOADED" => 'Y',
                                "INPUT_NAME" => "SUBSCRIBE_CONFIRM_PDP",
                                "REPLACE" => array(),
                            )
                        );?>
                        </div>
                        <div class="b-subscription__btns">
                            <input class="btn btn-primary" type="submit" name="Save" value="<?php echo($arResult["ID"] > 0 ? Loc::getMessage("CT_BSE_BTN_EDIT_SUBSCRIPTION"): Loc::getMessage("CT_BSE_BTN_ADD_SUBSCRIPTION"))?>">
                        </div>
                    </div>

                    <?php if ($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y"): ?>
                        <hr>
                        <div class="field-wrap">
                            <div class="label-wrap"><?=Loc::getMessage('CT_BSE_CONF_NOTE');?></div>
                            <input class="form-control" name="CONFIRM_CODE" type="text" value="" placeholder="<?=Loc::getMessage("CT_BSE_CONFIRMATION")?>"><br>
                            <input class="btn btn-primary" type="submit" name="confirm" value="<?=Loc::getMessage("CT_BSE_BTN_CONF")?>">
                        </div>
                    <?php endif; ?>
                </form>

                <?php if (!CSubscription::IsAuthorized($arResult["ID"])): ?>
                    <hr>
                    <form action="<?=$arResult["FORM_ACTION"]?>" method="post">
                        <?=bitrix_sessid_post();?>
                        <input type="hidden" name="action" value="sendcode">
                        <div class="field-wrap">
                            <div class="label-wrap"><?=Loc::getMessage('CT_BSE_SEND_NOTE');?></div>
                            <input class="form-control" name="sf_EMAIL" type="text" value="" placeholder="<?=Loc::getMessage("CT_BSE_EMAIL")?>"><br>
                            <input class="btn btn-primary" type="submit" value="<?=Loc::getMessage("CT_BSE_BTN_SEND")?>" />
                        </div>
                    </form>
                <?php endif; ?>
                <div class="field-wrap">
                  <span><span class="required">*</span><?=Loc::getMessage('RS.REQUIRE_NOTE')?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
