<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

$arAchivements = CUtil::JsObjectToPhp($arParams['~COMPANY_ACHIVEMENTS']);

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/waypoints/jquery.waypoints.js');
?>

<div id="catalog-bottom-container" style="text-align:center">
	<div style="display:inline-block; padding-right: 30px;" id="catalog-bottom-left">
		<div class="row" style="width: 540px;"></div>
	</div>
		<div style="display:inline-block; class="b-about-company js-about-company" id="js-about-company">
	    <?php if (!empty($arParams['RS_TITLE'])): ?>
	        <h3 class="b-about-company__title"><?=$arParams['RS_TITLE']?></h3>
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
	<div style="display:inline-block; padding-left: 30px;" id="catalog-bottom-right">
		<div class="row" style="width: 540px;"></div>
	</div>
</div>

