<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$jsParams = [
    'ajaxUrl' => $componentPath.'/ajax.php',
    'siteId' => SITE_ID,
    'confirmPopupId' =>  'location_confirm'
];

$arParams['POPUP_URL'] = (isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR.'mycity/');
?>
<div class="b-topline-location" id="topline-location">
    <svg class="icon-svg"><use xlink:href="#svg-location-main"></use></svg><?=Loc::getMessage('RS_LOCATION_YOUR_CITY_1');?>
    <?php
    $frame = $this->createFrame('topline-location')->begin();
    $frame->setBrowserStorage(true);
    ?>
    <a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" class="b-topline-location__link">
        <?=(!empty($arResult['NAME']) ? $arResult['NAME'] : Loc::getMessage('RS_LOCATION_NOT_SELECT')); ?>
    </a>

    <?php if (!empty($arResult['NAME'])): ?>
    <div class="b-location-confirm" id="location_confirm" style="display: none">
        <div class="b-location-confirm__triangle"></div>
        <div class="b-location-confirm__your"><?=Loc::getMessage('RS_LOCATION_YOUR_CITY', ['#CITY_NAME#' => $arResult['NAME']]); ?></div>
        <div class="b-location-confirm__controls">
            <a onclick="RS.Location.hideConfirm(); return false;" href="" class="btn btn-primary"><?=Loc::getMessage('RS_LOCATION_CITY_RIGHT');?></a>
            <a href="<?=$arParams['POPUP_URL']?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" class="btn btn-default"><?=Loc::getMessage('RS_LOCATION_CITY_SEARCH');?></a>
        </div>
    </div>
    <?php endif; ?>

    <script>
    RS.Location = new RSLocation(<?=CUtil::PhpToJSObject($arResult)?>, <?=CUtil::PhpToJSObject($jsParams)?>);
    </script>

    <?php $frame->beginStub(); ?>
        <a href="<?=(isset($arParams['POPUP_URL']) ? $arParams['POPUP_URL'] : SITE_DIR.'mycity/')?>" title="<?=Loc::getMessage('RS_LOCATION_SELECT');?>" data-type="ajax" class="b-topline-location__link"><?= Loc::getMessage('RS_LOCATION_NOT_SELECT'); ?></a>
    <?php $frame->end(); ?>
</div>
