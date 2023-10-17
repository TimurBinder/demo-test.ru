<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;
?>
<div class="b-footer-subscribe">
    <div class="b-footer-subscribe__title">
    <?php if (!empty($arParams['RS_TITLE_TEXT'])): ?>
        <?=$arParams['RS_TITLE_TEXT']?>
    <?php else: ?>
        <?=Loc::getMessage('RS.SF_TITLE_TEXT'); ?>
    <?php endif; ?>
    </div>
    <?php if (!empty($arParams['RS_NOTE_TEXT'])): ?>
        <div class="b-footer-subscribe__note">
            <div class="b-footer-subscribe__note-icon">
                <svg class="icon-svg icon-svg-mail">
                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-mail"></use>
                </svg>
            </div>
            <?=$arParams['RS_NOTE_TEXT']?>
        </div>
    <?php endif; ?>
    <?php $frame = $this->createFrame('footersubscribe', false)->begin(); ?>
    <form class="b-footer-subscribe__form" action="<?=$arResult['FORM_ACTION']?>">
        <?php foreach ($arResult['RUBRICS'] as $itemID => $itemValue): ?>
            <input class="hidden" type="checkbox" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?php if($itemValue["CHECKED"]) { echo ' checked'; }?> title="<?=$itemValue['NAME']?>">
        <?php endforeach; ?>
        <div class="input-group">
            <input type="text" name="sf_EMAIL" size="20" value="" placeholder="<?=Loc::getMessage('RS.SF_EMAIL_PLACEHOLDER');?>" class="form-control">
            <label class="input-group-btn">
                <svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-checkmark"></use></svg>
                <input name="OK" type="submit" class="btn btn-dark" value="">
            </label>
        </div>
    </form>
    <?php $frame->beginStub(); ?>
    <form class="b-footer-subscribe__form" action="<?=$arResult['FORM_ACTION']?>">
        <?php foreach ($arResult['RUBRICS'] as $itemID => $itemValue): ?>
            <input class="hidden" type="checkbox" name="sf_RUB_ID[]" value="<?=$itemValue["ID"]?>"<?php if($itemValue["CHECKED"]) { echo ' checked'; }?> title="<?=$itemValue['NAME']?>">
        <?php endforeach; ?>
        <div class="input-group">
            <input type="text" name="sf_EMAIL" size="20" value="" placeholder="<?=Loc::getMessage('RS.SF_EMAIL_PLACEHOLDER');?>" class="form-control">
            <label class="input-group-btn">
                <svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-checkmark"></use></svg>
                <input name="OK" type="submit" class="btn btn-dark" value="">
            </label>
        </div>
    </form>
    <?php $frame->end(); ?>
</div>
