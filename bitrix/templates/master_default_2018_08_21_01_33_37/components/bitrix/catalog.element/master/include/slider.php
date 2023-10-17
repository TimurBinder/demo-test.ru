<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

use \Bitrix\Main\Localization\Loc;
?>
<div class="product-item-detail-slider-container" id="<?=$itemIds['BIG_SLIDER_ID']?>">
    <div class="product-item-detail-slider-block
        <?/*<?=($arParams['IMAGE_RESOLUTION'] === '1by1' ? 'product-item-detail-slider-block-square' : '')?>*/?>"
        data-entity="images-slider-block">
        <span class="product-item-label-text <?=$labelPositionClass?>" id="<?=$itemIds['STICKER_ID']?>"
            <?=(!$arResult['LABEL'] ? 'style="display: none;"' : '' )?>>
            <?
            if ($arResult['LABEL'] && !empty($arResult['LABEL_ARRAY_VALUE']))
            {
                foreach ($arResult['LABEL_ARRAY_VALUE'] as $code => $value)
                {
                    $sLabelStyle = '';
                    if (substr($arResult['PROPERTIES'][$code]['VALUE_XML_ID'], 0, 1) == '#') {
                        $sLabelStyle = ' style="background:'.$arResult['PROPERTIES'][$code]['VALUE_XML_ID'].'"';
                    }
                    ?>
                    <span class="product-item-label-text-item<?=(!isset($arParams['LABEL_PROP_MOBILE'][$code]) ? ' hidden-xs' : '')?>">
                        <span title="<?=$value?>"<?if (strlen($sLabelStyle) > 0){ echo $sLabelStyle; }?>><?=$value?></span>
                    </span>
                    <?
                }
            }
            ?>
        </span>
        <?
/*
        if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y')
        {
            if ($haveOffers)
            {
                ?>
                <div class="product-item-label-ring <?=$discountPositionClass?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>"
                    style="display: none;">
                </div>
                <?
            }
            else
            {
                if ($price['DISCOUNT'] > 0)
                {
                    ?>
                    <div class="product-item-label-ring <?=$discountPositionClass?>" id="<?=$itemIds['DISCOUNT_PERCENT_ID']?>"
                        title="<?=-$price['PERCENT']?>%">
                        <span><?=-$price['PERCENT']?>%</span>
                    </div>
                    <?
                }
            }
        }
*/
        //if (isset($actualItem['DETAIL_PICTURE']) && !in_array($actualItem['DETAIL_PICTURE']['ID'], array_column($actualItem['MORE_PHOTO'], 'ID'))) {

        if (!empty($actualItem['MORE_PHOTO']))
        {
            if (is_array($actualItem['MORE_PHOTO']) && count($actualItem['MORE_PHOTO']) > 0) {
                
                $iSliderHeight = 0;
                $iSliderHeight = max(array_map(
                    function($arPhoto){
                        return intval($arPhoto['RESIZE']['height']);
                    },
                    array_filter($actualItem['MORE_PHOTO'], function($arPhoto) {
                        return (
                            strlen($arPhoto['SRC']) > 0
                        );
                    })
                ));
                
                if (intval($iSliderHeight) <= 0) {
                    $iSliderHeight = 300;
                }
                
                //$sSliderStyle = ' style="max-height:'.$iSliderHeight.'px"';
                ?>
                <div class="product-item-detail-slider-images-container"<?/* if ($iSliderHeight > 0) { echo ' style="max-height:'.$iSliderHeight.'px"'; } */?> data-entity="images-container">
                    <?php
                    foreach ($actualItem['MORE_PHOTO'] as $key => $arPhoto)
                    {
                        ?>
                        <a class="product-item-detail-slider-image"<?//=$sSliderStyle?> data-fancybox="gallery" data-caption="<?=$strTitle?>" href="<?=$arPhoto['SRC']?>" data-entity="image" data-id="<?=$arPhoto['ID']?>" data-dot="<span></span>"><?php
                            ?><img src="<?=isset($arPhoto['RESIZE']['src']) ? $arPhoto['RESIZE']['src'] : $arPhoto['SRC']?>" alt="<?=$alt?>" title="<?=$title?>"<?=($key == 0 ? ' itemprop="image"' : '')?>><?php
                        ?></a>
                        <?
                    }
                    ?>
                </div>    
                <?php
            }
        }
        ?>
    </div>
    <div class="owl-theme">
        <div class="product-item-detail-slider-dots owl-dots"></div>
    </div>
</div>