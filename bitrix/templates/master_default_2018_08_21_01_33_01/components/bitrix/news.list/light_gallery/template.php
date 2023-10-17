<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

$this->setFrameMode(true);

if(count($arResult['ITEMS']) > 0):
?>
<div class="b-light-gallery js-light-gallery">
    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    <div class="container">
    <?php endif; ?>
    <?php if (!empty($arParams['RS_TITLE'])): ?>
        <h3 class="block-title"><?=$arParams['RS_TITLE']?></h3>
    <?php endif; ?>
    <?php if($arParams['USE_OWL'] == 'Y'): ?>
    <div class="b-light-gallery__items owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
    <?php else: ?>
    <div class="b-light-gallery__items is-grid">
    <?php endif; ?>

    <?php foreach ($arResult['ITEMS'] as $arItem): ?>
        <?php
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="b-light-gallery__item"  id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <a href="#" class="b-light-gallery__item-link js-light-gallery__item" data-code="<?=$arItem['CODE']?>">
                <img class="b-light-gallery__item-img" src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>">
                <span class="b-light-gallery__item-title"><?=$arItem['NAME']?></span>
            </a>
        </div>
    <?php endforeach; ?>
    </div>

    <div class="js-light-gallery__allpictures">
    <?php foreach($arResult['ITEMS'] as $arItem): if(empty($arItem['PREVIEW_PICTURE'])) continue; ?>
        <?php if (isset($arItem['DETAIL_PICTURE']['SRC'])): ?>
              <a href="<?=$arItem['DETAIL_PICTURE']['SRC']?>" class="js-tourphoto js-tourphoto-<?=$arItem['CODE']?>" data-fancybox="js-tourphoto-<?=$arItem['CODE']?>" data-open-mobile="true"></a>
          <?php endif; ?>
          <?php if(isset($arItem['DISPLAY_PROPERTIES'][$arParams['RS_PROP_MORE_PHOTO']]['FILE_VALUE'])): ?>
              <?php foreach ($arItem['DISPLAY_PROPERTIES'][$arParams['RS_PROP_MORE_PHOTO']]['FILE_VALUE'] as $arFile): ?>
              <a href="<?=$arFile['SRC']?>" class="js-light-gallery__<?=$arItem['CODE']?>" data-fancybox="js-light-gallery__<?=$arItem['CODE']?>"></a>
              <?php endforeach; ?>
          <?php endif; ?>
    <?php endforeach; ?>
    </div>

    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?>
    </div>
    <?php endif; ?>

</div>
<?php endif; ?>
