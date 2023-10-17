<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$this->setFrameMode(true);

$skipProperties = array(
    $arParams['MARKER_TEXT_PROPERTY'],
    $arParams['MARKER_COLOR_PROPERTY'],
    $arParams['SALE_DATE_PROPERTY']
);

$arDisplayProps = array();
foreach ($arResult['DISPLAY_PROPERTIES'] as $arProp) {
    if (!in_array($arProp['CODE'], $skipProperties)) $arDisplayProps[] = $arProp;
}

$sLinkItems = '';
?>
<div class="b-news-detail" id="<?=$this->GetEditAreaId($arResult['ID'])?>>" itemscope itemtype="http://schema.org/Article">
    <?php if($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
    <time class="b-news-detail__date" itemprop="datePublished" datetime="<?=ConvertDateTime($arResult["ACTIVE_FROM"], 'YYYY-MM-DD')?>"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></time>
    <?php endif?>
    <?php if ($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])): ?>
    <div class="b-news-detail__img"  itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
        <img
          src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>"
          alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>"
          title="<?=$arResult["DETAIL_PICTURE"]["TITLE"]?>">
          <meta itemprop="width" content="<?=$arResult['DETAIL_PICTURE']['WIDTH']?>">
          <meta itemprop="height" content="<?=$arResult['DETAIL_PICTURE']['HEIGHT']?>">
    </div>
    <?php endif; ?>
    <?php if (isset($arResult['DISPLAY_PROPERTIES'][$arParams['MARKER_TEXT_PROPERTY']])): ?>
    <div class="b-sale-promotion-tag">
        <div class="b-sale-promotion-tag__marker" style="background-color: <?=(isset($arResult['DISPLAY_PROPERTIES'][$arParams['MARKER_COLOR_PROPERTY']]) ? $arResult['DISPLAY_PROPERTIES'][$arParams['MARKER_COLOR_PROPERTY']]['DISPLAY_VALUE']: '#000')?>"><?=$arResult['DISPLAY_PROPERTIES'][$arParams['MARKER_TEXT_PROPERTY']]['DISPLAY_VALUE']?></div>
        <?php if (isset($arResult['DISPLAY_PROPERTIES'][$arParams['SALE_DATE_PROPERTY']])): ?>
        <div class="b-sale-promotion-tag__date"><?=$arResult['DISPLAY_PROPERTIES'][$arParams['SALE_DATE_PROPERTY']]['DISPLAY_VALUE']?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="b-news-detail__content" itemprop="articleBody">
        <?php if (!empty($arResult['DETAIL_TEXT'])): ?>
            <?php echo $arResult["DETAIL_TEXT"]; ?>
        <?php else: ?>
            <?php echo $arResult["PREVIEW_TEXT"]; ?>
        <?php endif; ?>
    </div>
    <?php if (count($arDisplayProps) > 0):?>
        <div class="b-news-detail__props">
        <?php foreach ($arDisplayProps as $arProp): ?>
            <?php
            if ($arProp['PROPERTY_TYPE'] == 'E' && count($arProp['VALUE']) > 0):
                continue;
            elseif ($arProp['PROPERTY_TYPE'] == 'F' && count($arProp['DISPLAY_VALUE']) > 0): ?>
            <div class="b-news-prop b-news-prop--file">
                <h3 class="b-news-prop__name"><?=$arProp['NAME']?></h3>
                <div class="b-news-prop__value row">
                    <?php foreach ($arProp['DISPLAY_VALUE'] as $arFile):?>
                    <div class="col-xs-6 col-sm-6 col-md-4 col-lg-4">
                        <div class="b-news-prop-file">
                            <a class="b-news-prop-file__image" href="<?=$arFile['FILE_SRC']?>">
                                <img src="<?=SITE_TEMPLATE_PATH.'/assets/images/file.png'?>" alt="<?=$arFile['FILE_NAME']?>" title="<?=$arFile['FILE_NAME']?>">
                            </a>
                            <a class="b-news-prop-file__name" href="<?=$arFile['FILE_SRC']?>"><?=$arFile['FILE_NAME']?></a>
                            <a class="b-news-prop-file__ext" href="<?=$arFile['FILE_SRC']?>">
                                <?=Loc::getMessage('RS.FILE_DOWNLOAD');?>:
                                <?=isset($arFile['FILE_EXTENSION']) ? $arFile['FILE_EXTENSION'] : ''?>
                                <?=isset($arFile['FILE_SIZE']) ? $arFile['FILE_SIZE'] : ''?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="b-news-prop<?php if ($arProp['USER_TYPE'] == 'HTML') echo ' b-news-prop--text';?>">
                <div class="b-news-prop__name"><?=$arProp['NAME']?>:</div>
                <div class="b-news-prop__value"><?=$arProp['DISPLAY_VALUE']?></div>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
