<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (!empty($arResult['ITEMS'])):
?>
<div class="l-side-item">
    <?php if (!empty($arParams['BLOCK_TITLE'])): ?>
    <h3 class="l-side-item__title"><?=$arParams['BLOCK_TITLE']?></h3>
    <?php endif; ?>
    <div class="l-side-item__content" itemscope itemtype="http://schema.org/ItemList">
        <?php
        foreach ($arResult['ITEMS'] as $arItem):
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <article class="b-side-news-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>" itemprop="itemListElement" itemscope itemtype="http://schema.org/NewsArticle">
            <a class="b-side-news-item__link" href="<?=$arItem['DETAIL_PAGE_URL']?>" itemprop="name"><?=$arItem['NAME']?></a>
            <div class="b-side-news-item__meta">
                <?php if (isset($arItem['DISPLAY_ACTIVE_FROM'])): ?>
                <div class="b-side-news-item__date">
                    <div class="b-side-news-item__date-icon"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-calendar"></use></svg></div>
                    <time  datetime="<?=ConvertDateTime($arItem["ACTIVE_FROM"], 'YYYY-MM-DD')?>" itemprop="datePublished"><?=$arItem['DISPLAY_ACTIVE_FROM']?></time>
                </div>
                <?php endif; ?>
                <?php if (isset($arItem['SHOW_COUNTER'])): ?>
                    <div class="b-side-news-item__views">
                        <div class="b-side-news-item__views-icon"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-preview"></use></svg></div>
                        <?=$arItem['SHOW_COUNTER']?>
                    </div>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
