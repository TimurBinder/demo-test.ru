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

        $arSliderOptions = array(
            'changespeed' => intval($arParams['OWL_CHANGE_SPEED']) < 1
                ? 2000
                : $arParams['OWL_CHANGE_SPEED'],
            'changedelay' => intval($arParams['OWL_CHANGE_DELAY']) < 1
                ? 8000
                : $arParams['OWL_CHANGE_DELAY'],
        );
        
        //if (isset($actualItem['DETAIL_PICTURE']) && !in_array($actualItem['DETAIL_PICTURE']['ID'], array_column($actualItem['MORE_PHOTO'], 'ID'))) {
        $arPictIds = array();
        if (is_array($actualItem['MORE_PHOTO']) && count($actualItem['MORE_PHOTO']) > 0) {
            foreach ($actualItem['MORE_PHOTO'] as $arPhoto) {
                $arPictIds[] = $arPhoto['ID'];
            }
        }

        if (isset($actualItem['DETAIL_PICTURE']) && !in_array($actualItem['DETAIL_PICTURE']['ID'], $arPictIds)) {
            $arSliderPhoto = array_merge(array($actualItem['DETAIL_PICTURE']), $actualItem['MORE_PHOTO']);
        } else {
            $arSliderPhoto = $actualItem['MORE_PHOTO'];
        }
        if (!empty($arSliderPhoto))
        {
            if (is_array($arSliderPhoto) && count($arSliderPhoto) > 0) {
                
                $iSliderHeight = 0;
                
                foreach ($arSliderPhoto as $key => $arPhoto)
                {
                    if ($arPhoto['ID'] > 0) {
                        $arSliderPhoto[$key]['RESIZE'] = CFile::ResizeImageGet(
                            $arPhoto['ID'],
                            array('width' => 450, 'height' => 450),
                            BX_RESIZE_IMAGE_PROPORTIONAL,
                            true
                        );
                    }
                }
                unset($key, $arPhoto);
                
                $iSliderHeight = max(array_map(
                    function($arPhoto){
                        return intval($arPhoto['RESIZE']['height']);
                    },
                    array_filter($arSliderPhoto, function($arPhoto) {
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
                <div class="product-item-detail-slider-images-container"<?php if ($iSliderHeight > 0) { echo ' style="max-height:'.$iSliderHeight.'px"'; } ?> data-entity="images-container" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arSliderOptions)?>'>
                    <?php
                    foreach ($arSliderPhoto as $key => $arPhoto)
                    {
                        ?>
                        <a class="product-item-detail-slider-image"<?//=$sSliderStyle?> data-fancybox="gallery" data-caption="<?=$strTitle?>" href="<?=$arPhoto['SRC']?>" data-entity="image" data-id="<?=$arPhoto['ID']?>"><?php
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