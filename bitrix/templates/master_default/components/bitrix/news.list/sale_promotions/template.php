<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);


if (count($arResult['ITEMS']) > 0):
?>
<div class="b-sale-promotions">
    <div class="container">
        <?php if (isset($arParams['RS_SHOW_TITLE'])): ?>
            <?php if (!empty($arParams['RS_TITLE'])): ?>
            <h2 class="b-sale-promotions__title"><?=$arParams['RS_TITLE']?></h2>
            <?php else: ?>
            <a class="b-sale-promotions__title" href="<?=$arResult['ITEMS'][0]['LIST_PAGE_URL']?>"><h2><?=$arResult['NAME']?></h2></a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($arParams['USE_OWL'] == 'Y'): ?>
        <div class="b-sale-promotions__items owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
        <?php else: ?>
        <div class="b-sale-promotions__items row">
        <?php endif; ?>
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?php if ($arParams['USE_OWL'] != 'Y'): ?> <div class="col-sm-<?=$arParams['COLS_IN_ROW']?>"> <?php endif; ?>

                <div class="b-sale-promotions__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                    <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                    <div class="b-sale-promotions__pic" itemscope="" itemtype="https://schema.org/ImageObject">
                        <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['MARKER_TEXT_PROPERTY']])): ?>
                        <div class="b-sale-promotions__tag" style="background: <?=isset($arItem['DISPLAY_PROPERTIES'][$arParams['MARKER_COLOR_PROPERTY']]) ? $arItem['DISPLAY_PROPERTIES'][$arParams['MARKER_COLOR_PROPERTY']]['DISPLAY_VALUE'] : '#000' ?>">
                            <?=$arItem['DISPLAY_PROPERTIES'][$arParams['MARKER_TEXT_PROPERTY']]['DISPLAY_VALUE']; ?>
                        </div>
                        <?php endif; ?>
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" >
                            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" itemprop="contentUrl">
                        </a>
                        <meta itemprop="width" content="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>">
                        <meta itemprop="height" content="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>">
                    </div>
                    <?php endif; ?>
                    <div class="b-sale-promotions__data">
                        <div class="b-sale-promotions__datainner">
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-sale-promotions__name" itemprop="name"><?=$arItem['NAME']?></a>
                            <?php if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['SALE_DATE_PROPERTY']])): ?>
                                <div class="b-sale-promotions__date"><?=$arItem['DISPLAY_PROPERTIES'][$arParams['SALE_DATE_PROPERTY']]['DISPLAY_VALUE'];?></div>
                            <?php endif; ?>
                            <div class="b-sale-promotions__desc"><?=$arItem['PREVIEW_TEXT']?></div>
                        </div>
                    </div>
                </div>
              <?php if ($arParams['USE_OWL'] != 'Y'): ?> </div> <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif;
