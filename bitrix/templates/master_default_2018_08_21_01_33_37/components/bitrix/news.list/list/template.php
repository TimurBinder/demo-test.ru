<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0):
?>
<div class="b-newslist">
    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?> <div class="container"> <?php endif; ?>
        <?php if (isset($arParams['RS_SHOW_TITLE']) && $arParams['RS_SHOW_TITLE'] == 'Y'): ?>
            <?php if (!empty($arParams['RS_TITLE'])): ?>
            <h3 class="b-newslist__title"><?=$arParams['RS_TITLE']?></h3>
            <?php else: ?>
            <a class="b-newslist__title" href="<?=$arResult['ITEMS'][0]['LIST_PAGE_URL']?>"><h3><?=$arResult['NAME']?></h3></a>
            <?php endif; ?>
        <?php endif; ?>
        <?php if($arParams['DISPLAY_TOP_PAGER']):?>
        <div class="text-center"><?=$arResult["NAV_STRING"]?></div>
        <?php endif; ?>
        <?php if ($arParams['USE_OWL'] == 'Y'): ?>
        <div class="b-newslist__items owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
        <?php else: ?>
        <div class="b-newslist__items row">
        <?php endif; ?>
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?php if ($arParams['USE_OWL'] != 'Y'): ?> <div class="col-sm-<?=$arParams['COLS_IN_ROW']?>"> <?php endif; ?>

                <div class="b-newslist__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemscope itemtype="http://schema.org/NewsArticle">
                    <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                    <div class="b-newslist__pic" itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" >
                            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" itemprop="contentUrl">
                        </a>
                        <meta itemprop="width" content="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>">
                        <meta itemprop="height" content="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>">
                    </div>
                    <?php endif; ?>
                    <div class="b-newslist__data">
                        <time class="b-newslist__date" itemprop="datePublished"><?=$arItem['DISPLAY_ACTIVE_FROM']?></time>
                        <div class="b-newslist__datainner">
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-newslist__name" itemprop="name"><?=$arItem['NAME']?></a>
                            <div class="b-newslist__desc" itemprop="description"><?=$arItem['PREVIEW_TEXT']?></div>
                        </div>
                    </div>
                </div>
              <?php if ($arParams['USE_OWL'] != 'Y'): ?> </div> <?php endif; ?>
        <?php endforeach; ?>
        </div>
        <?php if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <div class="text-center"><?=$arResult["NAV_STRING"]?></div>
        <?php endif; ?>
    <?php if ($arParams['ADD_CONTAINER'] == 'Y'): ?> </div> <?php endif; ?>
</div>
<?php endif;
