<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arAchivements = CUtil::JsObjectToPhp($arParams['~COMPANY_ACHIVEMENTS']);

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/waypoints/jquery.waypoints.js');
?>
<div class="b-about-company js-about-company">
    <?php if (!empty($arParams['RS_TITLE'])): ?>
        <h2 class="b-about-company__title"><?=$arParams['RS_TITLE']?></h2>
    <?php endif; ?>
    <div class="b-about-company__content">
        <?php
        if($arResult["FILE"] <> '') {
            include($arResult["FILE"]);
        }
        ?>
    </div>
    <?php if (count($arAchivements) > 0): ?>
    <div class="b-about-company__achievements">
        <?php foreach ($arAchivements as $arAchivement): ?>
        <div class="b-about-company__achievement">
            <div class="b-about-company__achievement-number js-about-company__number" data-number="<?=$arAchivement[0]?>">0</div>
            <div class="b-about-company__achievement-desc"><?=$arAchivement[1]?></div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
