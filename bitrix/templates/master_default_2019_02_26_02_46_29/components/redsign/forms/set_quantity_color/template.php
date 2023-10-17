<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

if (!isset($arParams['DISABLED_FIELDS']) || !is_array($arParams['DISABLED_FIELDS'])) {
    $arParams['DISABLED_FIELDS'] = array();
}

//echo "<pre>";
//print_r($arResult['FIELDS']);
//echo "</pre>";
?>
<div class="rsform">
    <?php if (count($arResult['MESSAGES']['ERRORS']) > 0): ?>
    <div class="alert alert-danger"><?php foreach ($arResult['MESSAGES']['ERRORS'] as $msg): ?><?=$msg?><br><?php endforeach; ?></div>
    <script>$(document).trigger("rs_forms.error");</script>
    <?php endif; ?>
    <?php if (count($arResult['MESSAGES']['SUCCESS']) > 0): ?>
    <div class="alert alert-success"><?php foreach ($arResult['MESSAGES']['SUCCESS'] as $msg): ?><?=$msg?><br><?php endforeach; ?></div>
    <script>$(document).trigger("rs_forms.success");</script>
    <?php endif; ?>

    <form action="<?=$arResult['REQUEST_URI']?>" method="POST" class="form rsfrom__form" id="<?=$arResult['FORM_NAME']?>">
        <?=bitrix_sessid_post()?>
        <div class="rsform__all-field clearfix">
            <?php
            foreach ($arResult['FIELDS'] as $key => $arField):
                $disabled = in_array($arField['CODE'], $arParams['DISABLED_FIELDS']) ? ' disabled' : '';
                $readonly =  in_array($arField['CODE'], $arParams['DISABLED_FIELDS']) ? ' readonly' : '';
            ?>
            <div class="row">
                <?php if ($arField['PROPERTY_TYPE'] == 'S'): ?>
                <div class="form-group col col-md-12 field-wrap">
                      <label for="FIELD_<?=$arField['CODE']?>" class="control-label">
                          <?=$arField['NAME']?>:
                          <?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
                      </label>
                      <?php if ($arField['USER_TYPE'] == 'HTML'): ?>
                          <?php $arUserTypeSettings = unserialize($arField['USER_TYPE_SETTINGS']); ?>
                          <textarea id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" class="form-control<?=$disabled?>" style="height: <?=$arUserTypeSettings['height']?>px"<?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>><?=$arField['CURRENT_VALUE']?></textarea>
                      <?php elseif ($arField['USER_TYPE'] == 'Date'): ?>
                          <input type="date" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>" <?=$readonly?><?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>>
                      <?php elseif ($arField['USER_TYPE'] == 'DateTime'): ?>
                          <input type="datetime-local" id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>" <?=$readonly?><?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>>
                      <?php else: ?>
                          <input <?php if(isset($arField['MASK'])) echo 'data-inputmask="\'mask\': \''.$arField['MASK'].'\'"'; ?> type="<?=$arField['INPUT_TYPE']?>"<?php if(!empty($arField['PATTERN'])) echo ' pattern="'.$arField['PATTERN'].'"'; ?> id="FIELD_<?=$arField['CODE']?>" name="FIELD_<?=$arField['CODE']?>" value="<?=$arField['CURRENT_VALUE']?>" class="form-control<?=$disabled?>"<?=$readonly?><?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>>
                      <?php endif; ?>
                </div>
                <?php elseif ($arField['PROPERTY_TYPE'] == 'L' && is_array($arField['VALUES'])): ?>
                    <div class="form-group col col-md-12 field-wrap">
                        <label for="FIELD_<?=$arField['CODE']?>" class="control-label">
                            <?=$arField['NAME']?>:
                            <?php if ($arField['IS_REQUIRED'] == 'Y'): ?><span class="required">*</span><?php endif; ?>
                        </label>
                        <select class="form-control<?=$disabled?>" name="FIELD_<?=$arField['CODE']?>" id="FIELD_<?=$arField['CODE']?>"<?=$disabled?><?php if ($arField['IS_REQUIRED'] == 'Y') echo ' required'?>>
                        <?php foreach ($arField['VALUES'] as $i => $arValue): ?>
                            <option <?php if ((empty($arField['CURRENT_VALUE']) && $i == 0) || $arField['CURRENT_VALUE'] == $arValue['ID']): ?>selected="selected"<?php endif; ?> value="<?=$arValue['ID']?>"><?=$arValue['VALUE']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ($arResult['USE_CAPTCHA'] == 'Y'): ?>
        <div class="row">
           <div class="form-group col col-md-12 field-wrap">
              <div class="captcha_wrap">
                 <div class="rsform__captcha-label"><label for="<?=$arResul['FORM_NAME']?>_captcha_word"><?=Loc::getMessage('MSG_CAPTHA'); ?> <span class="required">*</span></label></div>
                 <div class="row">
                    <div class="rsform__captcha-input col col-md-6 col-xs-12">
                       <input type="hidden" name="captcha_sid" value="<?=$arResult['CAPTCHA_CODE']?>">
                       <input class="form-control req-input form-item" type="text" name="captcha_word" id="<?=$arResul['FORM_NAME']?>_captcha_word" size="30" maxlength="50" value="" required>
                    </div>
                    <div class="rsform__captcha-image col col-md-6 col-xs-12">
                       <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult['CAPTCHA_CODE']?>" alt="CAPTCHA">
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <?php endif; ?>
        <?php if ($arParams['USER_CONSENT'] == 'Y'):?>
        <div class="row">
            <div class="col-xs-12 field-wrap">
                <div class="rsform__confirm">
					<?php
					$sBtnSubmitText = isset($arParams['~MESS_SUBMIT'])
						? $arParams['~MESS_SUBMIT']
						: Loc::getMessage('MSG_SUBMIT');
					?>
                    <?$APPLICATION->IncludeComponent(
                        "bitrix:main.userconsent.request",
                        "form",
                        array(
                            "ID" => $arParams["USER_CONSENT_ID"],
                            "IS_CHECKED" => $arParams["USER_CONSENT_IS_CHECKED"],
                            "IS_LOADED" => $arParams["USER_CONSENT_IS_LOADED"],
                            "AUTO_SAVE" => "Y",
							// "ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
							// "ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
							"INPUT_NAME" => "FORM",
							// 'SUBMIT_EVENT_NAME' => '',
							'REPLACE' => array(
								'button_caption' => $sBtnSubmitText,
								// 'fields' => array()
							)
						)
                    );?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="rsform__bottom">
            <div class="rsform__bottom-button">
                <input type="hidden" name="PARAMS_HASH" value="<?=$arResult['PARAMS_HASH']?>">
                <input type="submit" class="btn btn-primary" name="submit" value="<?=$sBtnSubmitText?>">
            </div>
            <div class="rsform__bottom-ps"><?=Loc::getMessage('MSG_REQUIRED_FIELDS')?></div>
        </div>
    </form>
</div>
<script>
  $("#<?=$arResult['FORM_NAME']?>").validator({focus: false});
  $("#<?=$arResult['FORM_NAME']?>").find('input[data-inputmask]').inputmask();
</script>
