<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

$sNewsId = $this->GetEditAreaId($arResult['ID']);

$arSliderOptions = array(
    'changespeed' => intval($arParams['OWL_CHANGE_SPEED']) < 1
        ? 2000
        : $arParams['OWL_CHANGE_SPEED'],
    'changedelay' => intval($arParams['OWL_CHANGE_DELAY']) < 1
        ? 8000
        : $arParams['OWL_CHANGE_DELAY'],
);

$arPropSkip = array();
if (isset($arResult['DISPLAY_PROPERTIES'][$arParams['ADD_PICT_PROP']])) {
    $arPropSkip[] = $arParams['ADD_PICT_PROP'];
}

$sLinkItems = '';
?>
<div class="b-news-detail" id="<?=$sNewsId?>" itemscope itemtype="http://schema.org/Article">

    <div class="b-news-detail__topline">

        <?php if($arParams["DISPLAY_DATE"] != "N" && $arResult["DISPLAY_ACTIVE_FROM"]):?>
        <span class="b-news-detail__topline-block b-news-detail__topline-block--date">
            <span class="b-news-detail__topline-icon"><svg class="icon-svg"><use xlink:href="#svg-calendar"></use></svg></span>
            <time class="b-news-detail__date" itemprop="datePublished" datetime="<?=ConvertDateTime($arResult["ACTIVE_FROM"], 'YYYY-MM-DD')?>"><?=$arResult["DISPLAY_ACTIVE_FROM"]?></time>
        </span>
        <?php endif?>

        <?php if (isset($arResult['SHOW_COUNTER'])): ?>
        <span class="b-news-detail__topline-delimiter"> | </span>
        <span class="b-news-detail__topline-block b-news-detail__topline-block--counter">
            <span class="b-news-detail__topline-icon"><svg class="icon-svg"><use xlink:href="#svg-preview"></use></svg></span>
            <span class="b-news-detail__counter"><?=$arResult['SHOW_COUNTER']?></span>
        </span>
        <?php endif; ?>

        <?php if ($arParams['SHOW_READING_TIME'] == 'Y'): ?>
        <span class="b-news-detail__topline-block b-news-detail__topline-block--time-reading"><?=Loc::getMessage('RS.ND_READING_TIME')?>: <span class="js-reading-time"></span></span>
        <?php endif; ?>
    </div>

    <?php if ($arParams['SHOW_PREVIEW_TEXT'] == 'Y' && !empty($arResult['PREVIEW_TEXT'])): ?>
    <div class="b-news-detail__preview">
        <?=$arResult['PREVIEW_TEXT']?>
    </div>
    <?php endif; ?>


    <?php if ($arParams["DISPLAY_PICTURE"] != "N"): ?>

        <?php
        if (!empty($arResult['PRODUCT_PHOTO']))
        {
            if (is_array($arResult['PRODUCT_PHOTO']) && count($arResult['PRODUCT_PHOTO']) > 0) {

                $iSliderHeight = 0;


                $iSliderHeight = max(array_map(
                    function($arPhoto){
                        return intval($arPhoto['RESIZE']['big']['height']);
                    },
                    array_filter($arResult['PRODUCT_PHOTO'], function($arPhoto) {
                        return (
                            strlen($arPhoto['SRC']) > 0
                        );
                    })
                ));

                if (intval($iSliderHeight) <= 0) {
                    $iSliderHeight = 450;
                }

                //$sSliderStyle = ' style="height:'.$iSliderHeight.'px"';
                ?>
                <div class="b-news-detail__slider gallery_slider">
                    <div class="gallery_slider__photo"<?php if ($iSliderHeight > 0) { echo ' style="max-height:'.$iSliderHeight.'px"'; } ?> data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?>'>
                        <?php
                        foreach ($arResult['PRODUCT_PHOTO'] as $key => $arPhoto)
                        {
                            $strAlt = isset($arPhoto['ALT']) && strlen($arPhoto['ALT']) > 0
                                ? $arPhoto['ALT']
                                : $arPhoto['DESCRIPTION'];

                            $strTitle = isset($arPhoto['TITLE']) && strlen($arPhoto['TITLE']) > 0
                                ? $arPhoto['TITLE']
                                : $arPhoto['DESCRIPTION'];
                            ?>
                            <a class="gallery_slider__canvas b-news-detail__img"<?//=$sSliderStyle?> data-entity="image" data-fancybox="gallery" data-caption="<?=$strTitle?>" href="<?=$arPhoto['SRC']?>" data-id="<?=$arPhoto['ID']?>" itemprop="image" itemscope itemtype="https://schema.org/ImageObject"  data-dot="<span class='owl-preview' style='background-image:url(<?=$arPhoto['RESIZE']['small']['src']?>);' alt='<?=$arPhoto['DESCRIPTION'];?>'></span>"><?php
                                ?><img src="<?=isset($arPhoto['RESIZE']['big']['src']) ? $arPhoto['RESIZE']['big']['src'] : $arPhoto['SRC']?>" alt="<?=$strAlt?>" title="<?=$strTitle?>"><?php
                            ?></a>
                            <?
                        }
                        ?>
                    </div>

                    <div class="owl-carousel owl-loaded owl-theme">
                        <div class="gallery_slider__dots owl-dots"></div>
                    </div>

                    <div class="gallery_slider__scroll scrollbar-outer">
                        <div class="gallery_slider__scroll-x scroll-element scroll-x">
                            <div class="scroll-arrow scroll-arrow_less"></div>
                            <div class="scroll-arrow scroll-arrow_more"></div>
                            <div class="scroll-element_outer">
                                <div class="scroll-element_size"></div>
                                <div class="scroll-element_track"></div>
                                <div class="scroll-bar"></div>
                            </div>
                        </div>
                    </div>

                </div>
                <?php
            }
        }
        ?>
    <?php endif; ?>

    <div class="b-news-detail__content" itemprop="articleBody">
        <div class="eta"></div>
        <?php if (!empty($arResult['DETAIL_TEXT'])): ?>
            <?php echo $arResult["DETAIL_TEXT"]; ?>
        <?php else: ?>
            <?php echo $arResult["PREVIEW_TEXT"]; ?>
        <?php endif; ?>
    </div>
    <?php if (count($arResult['DISPLAY_PROPERTIES']) > 0): ?>
    <div class="b-news-detail__props">
    <?php foreach ($arResult['DISPLAY_PROPERTIES'] as $pid => $arProp): ?>
        <?php
        if (in_array($pid, $arPropSkip)) {
            continue;
        }
        ?>
        <?php
        if ($arProp['PROPERTY_TYPE'] == 'E' && count($arProp['VALUE']) > 0):
            continue;
        elseif ($arProp['PROPERTY_TYPE'] == 'F' && count($arProp['DISPLAY_VALUE']) > 0):
        ?>
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
        <?php
        else:
        ?>
        <div class="b-news-prop<?php if ($arProp['USER_TYPE'] == 'HTML') echo ' b-news-prop--text';?>">
            <div class="b-news-prop__name"><?=$arProp['NAME']?>:</div>
            <div class="b-news-prop__value"><?=is_array($arProp['DISPLAY_VALUE']) ? implode(' / ', $arProp['DISPLAY_VALUE']) : $arProp['DISPLAY_VALUE']?></div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php
if ($arParams['SHOW_READING_TIME'] == 'Y'):
    $this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/js/reading_time.js');
?>
<script>
RS.ReadingTime($("#<?=$sNewsId?>"), {
  wordsPerMinute: 130,
  lang: {
    lessThanAMinute: '<?=Loc::getMessage('RS.ND_LESS_THAN_A_MINUTE')?>',
    minutes: [
      '<?=Loc::getMessage('RS.ND_MINUTE_TITLE_1')?>',
      '<?=Loc::getMessage('RS.ND_MINUTE_TITLE_2')?>',
      '<?=Loc::getMessage('RS.ND_MINUTE_TITLE_3')?>'
    ]
  }
})
.done(function (result) {
  $("#<?=$sNewsId?>").find('.js-reading-time').text(result);
});
</script>
<?php endif;
