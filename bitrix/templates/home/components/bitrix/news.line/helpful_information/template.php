<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (count($arResult['ITEMS']) > 0):
?>
<div class="b-helpful-info">
    <div class="container">
        <div class="row">
            <h3 class="col-sm-6 b-helpful-info__title">
            <?php if (!empty($arParams['RS_TITLE'])): ?>
                <?=$arParams['RS_TITLE']?>
            <?php else: ?>
                <?=Loc::getMessage('RS.HELPINFO_TITLE'); ?>
            <?php endif; ?>
            </h3>
            <div class="col-sm-6 b-helpful-info__tools">
            <?php if(!empty($arParams['RS_SUMMARY_PAGE'])): ?>
                <a href="<?=$arParams['RS_SUMMARY_PAGE']?>" class="b-helpful-info__showall"><?=Loc::getMessage('RS.SHOW_ALL');?></a>
            <?php endif; ?>
            <?php if ($arParams['USE_OWL'] == 'Y'): ?><div class="b-helpful-info__nav"></div><?php endif; ?>
            </div>
        </div>
        <?php if ($arParams['USE_OWL'] == 'Y'): ?>
        <div class="b-helpful-info__items owl owl-carousel owl-theme" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
        <?php else: ?>
        <div class="b-helpful-info__items row">
        <?php endif; ?>
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <?php
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>
            <?php if ($arParams['USE_OWL'] != 'Y'): ?> <div class="col-sm-<?=$arParams['COLS_IN_ROW']?>"> <?php endif; ?>

                <div class="b-helpful-info__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemscope itemtype="http://schema.org/NewsArticle">
                    <a href="<?=$arResult['IBLOCKS'][$arItem['IBLOCK_ID']]['LIST_PAGE_URL']?>" class="b-helpful-info__tag" style="background-color: <?=isset($arParams['RS_TAG_'.$arItem['IBLOCK_ID'].'_COLOR']) ? $arParams['RS_TAG_'.$arItem['IBLOCK_ID'].'_COLOR']: '#000';?>">
                        <?=$arItem['IBLOCK_NAME']?>
                    </a>
                    <?php if (!empty($arItem['PREVIEW_PICTURE']['SRC'])): ?>
                    <div class="b-helpful-info__pic" itemprop="image" itemscope="" itemtype="https://schema.org/ImageObject">
                        <a href="<?=$arItem['DETAIL_PAGE_URL']?>" >
                            <img src="<?=$arItem['PREVIEW_PICTURE']['SRC']?>" alt="<?=$arItem['PREVIEW_PICTURE']['ALT']?>" title="<?=$arItem['PREVIEW_PICTURE']['TITLE']?>" itemprop="contentUrl">
                        </a>
                        <meta itemprop="width" content="<?=$arItem['PREVIEW_PICTURE']['WIDTH']?>">
                        <meta itemprop="height" content="<?=$arItem['PREVIEW_PICTURE']['HEIGHT']?>">
                    </div>
                    <?php endif; ?>
                    <div class="b-helpful-info__data">
                        <time class="b-helpful-info__date" itemprop="datePublished"><?=$arItem['DISPLAY_ACTIVE_FROM']?></time>
                        <div class="b-helpful-info__datainner">
                            <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="b-helpful-info__name" itemprop="name"><?=$arItem['NAME']?></a>
                            <div class="b-helpful-info__desc" itemprop="description"><?=$arItem['PREVIEW_TEXT']?></div>
                        </div>
                    </div>
                </div>
              <?php if ($arParams['USE_OWL'] != 'Y'): ?> </div> <?php endif; ?>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif;
