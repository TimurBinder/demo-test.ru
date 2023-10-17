<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
?>

<style>
   <?php if($arResult['IS_JS_HEIGHT_ADJUST'] != "Y"): ?>
       .rs-banners .rs-banners_banner,
       .rs-banners-sidebanner,
       .rs_banner-preloader {
           height: 60vw;
       }
       @media(min-width: 768px) {
           .rs-banners .rs-banners_banner,
           .rs-banners-sidebanner,
           .rs_banner-preloader {
               height: <?=$arResult['BANNER_HEIGHT']?>
           }
       }

       @media (min-width: 992px) {
            .is-page-home .b-vertical-menu__navbar,
            .is-page-home .b-vertical-menu__nav {
                min-height: <?=$arResult['BANNER_HEIGHT']?>;
                
            }
            
       }

   <?php endif; ?>
</style>

<div class="rs-banners-container js-mainbanners-container <?=$arResult['BANNER_CLASS']?>"
    style="opacity: 0; transition: opacity 1s;">

    <div class="rs-banners-sidebanner __left js-sidebanners <?php if(in_array("left", $arResult['SELECTED_SIDEBANNERS'])) {echo 'js-sidebanners_selected';} ?>"
         style="display: none;">
        <?php foreach($arResult['SIDEBANNERS']['LEFT'] as $arImage): ?>
            <div class="rs-banners-sidebanner_image">
                <a href="<?$arImage['link']?>">
                    <img src="<?=$arImage['src']?>">
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="rs-banners-sidebanner __right js-sidebanners <?php if(in_array("right", $arResult['SELECTED_SIDEBANNERS'])) {echo 'js-sidebanners_selected';} ?>"
        style="  display: none;">
        <?php foreach($arResult['SIDEBANNERS']['RIGHT'] as $arImage): ?>
            <div class="rs-banners-sidebanner_image">
                <a href="<?$arImage['link']?>">
                    <img src="<?=$arImage['src']?>">
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div
        class="js-banner-options"
        style="display: none;"
        <?php foreach($arResult['BANNER_OPTIONS'] as $optionName => $optionValue): ?>
            <?php if(is_bool($optionValue)): ?>
                data-<?=$optionName?>="<?=$optionValue ? 'true' : 'false'?>"
            <?php elseif(is_array($optionValue)): ?>

            <?php else: ?>
                data-<?=$optionName?>="<?=$optionValue?>"
            <?php endif; ?>
        <?php endforeach; ?>
    ></div>

    <div class="rs-banners js-banners owl-master owl-theme owl-carousel" style=" display: none;">

    <?php foreach($arResult['ITEMS'] as $arItem): ?>

        <?php
         $this->AddEditAction(
            $arItem['ID'],
            $arItem['EDIT_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT")
        );
        $this->AddDeleteAction(
            $arItem['ID'],
            $arItem['DELETE_LINK'],
            CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"),
            array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM'))
        );
        ?>

        <div class="rs-banners_banner" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <?php if($arItem['VIDEO_TYPE'] == 'frame'): ?>
                <a class="owl-video" href="<?=$arItem['VIDEO_URL']?>"></a>
            <?php elseif($arItem['VIDEO_TYPE'] == 'file'): ?>
                <div class="rs-banners_video" data-play="false">
                    <video src="<?=$arItem['VIDEO_URL']?>"></video>
                </div>
                <div class="rs-banners_video-play"></div>
                <div class="rs-banners_wrap">
                        <div class="rs-banners_infowrap rs-banners_infovideo">
                            <div class="rs-banners_info">
                                <?php if(!empty($arItem['PRODUCT_BEFORE_TITLE'])): ?>
                                    <div class="rs-banners_before-title rs-banners_video-blockwrap">
                                        <?=$arItem['PRODUCT_BEFORE_TITLE'];?>
                                    </div>
                                <?php endif; ?>
                                <?php if(!empty($arItem['PRODUCT_TITLE'])): ?>
                                    <div class="rs-banners_title rs-banners_video-blockwrap">
                                        <?=$arItem['PRODUCT_TITLE']?>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($arItem['PRODUCT_PRICE'])): ?>
                                    <div class="rs-banners_price rs-banners_video-blockwrap">
                                        <?=$arItem['PRODUCT_PRICE']?>
                                    </div>
                                <?php endif; ?>

                                <?php if(!empty($arItem['PRODUCT_DESC'])): ?>
                                    <div class="rs-banners_desc rs-banners_video-blockwrap">
                                        <?=$arItem['PRODUCT_DESC']?>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>
            <?php else: ?>


                <?php if(!empty($arItem['BACKGROUND'])): ?>
                <div
                    class="rs-banners_background"
                    data-img-src="<?=$arItem['BACKGROUND']?>"
                    style="background-image:url('<?=$arItem['BACKGROUND']?>')"
                >
                    <img src="<?=$arItem['BACKGROUND']?>" style="width: 0px; height: 0px;">
                </div>
                <?php endif; ?>


                <div class="rs-banners_wrap">
                    <?php if(!empty($arItem['PRODUCT_IMG'])): ?>

                        <div class="rs-banners_product">
                            <img
                                data-img-src="<?=$arItem['PRODUCT_IMG']?>"
                                src="<?=$arItem['PRODUCT_IMG']?>"
                                alt="<?=$arItem['NAME']?>"
                            >
                        </div>

                    <?php endif; ?>

                        <div class="rs-banners_infowrap">
                            <div class="rs-banners_info"><?php
                                $transitionDelayIndex = 1;
                                ?><?php if(!empty($arItem['PRODUCT_BEFORE_TITLE'])): ?>
                                    <div class="rs-banners_before-title rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?> js-banners-before-title">
                                        <?=$arItem['PRODUCT_BEFORE_TITLE'];?>
                                    </div>
                                <?php
                                $transitionDelayIndex++;
                                endif;
                                ?>
                                <?php if(!empty($arItem['PRODUCT_TITLE'])): ?>
                                    <?php if(!empty($arItem['PRODUCT_BEFORE_TITLE'])): ?><br><?php endif; ?>
                                    <div
                                        class="rs-banners_title rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?> js-banners-title"
                                        style="<?=!(empty($arItem['PRODUCT_TITLE_BACKGROUND'])) ? 'background: '.htmlspecialcharsbx($arItem['PRODUCT_TITLE_BACKGROUND']).';' : ''?>"
                                    >
                                        <?=$arItem['PRODUCT_TITLE'];?>
                                    </div>
                                <?php
                                $transitionDelayIndex++;
                                endif;
                                ?>
                                <?php if(!empty($arItem['PRODUCT_PRICE'])): ?>
                                    <br>
                                    <div
                                        class="rs-banners_price rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?>"
                                        style="<?=!(empty($arItem['PRODUCT_PRICE_BACKGROUND'])) ? 'background: '.htmlspecialcharsbx($arItem['PRODUCT_PRICE_BACKGROUND']).';' : ''?>"
                                    >
                                        <?=$arItem['PRODUCT_PRICE'];?>
                                    </div>
                                <?php
                                $transitionDelayIndex++;
                                endif;
                                ?>
                                <?php if(!empty($arItem['PRODUCT_DESC'])): ?>
                                    <br>
                                    <div class="rs-banners_desc rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?>">
                                        <?=$arItem['PRODUCT_DESC'];?>
                                    </div>
                                <?php
                                $transitionDelayIndex++;
                                endif;
                                ?>
                                <?php /*if(!empty($arItem['PRODUCT_BUTTON_TEXT'])): ?>
                                    <br>
                                    <a class="rs-banners_button btn btn-primary rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?>" href="<?=$arItem['PRODUCT_LINK']?>" target="_blank" title="<?=$arItem['PRODUCT_BUTTON_TEXT']?>">
                                        <svg class="icon-svg rs-banners_button-icon"><use xlink:href="#svg-arrow-thin-right"></use></svg>
                                    </a>
                                <?php endif; */?>
                                <?php if(!empty($arItem['PRODUCT_BUTTON_TEXT'])): ?>
                                    <br>
                                    <a href="<?=$arItem['PRODUCT_LINK']?>" target="_blank" class="rs-banners_button btn btn-primary rs-banners-transform rs-banners-transform-delay-<?=$transitionDelayIndex?>">
                                        <?=$arItem['PRODUCT_BUTTON_TEXT']?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                </div>
                <?php if(!empty($arItem['PRODUCT_LINK'])): ?>
                    <a href="<?=$arItem['PRODUCT_LINK']?>" target="_blank" class="rs-banners_link"></a>
                <?php endif; ?>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>
    </div>

    <div class="rs-banners_bottom-line"></div>

</div>
<div class="js-preloader rs_banner-preloader preloader" style="width: 100%;"></div>
<script>
    rsBannersOnReady();
</script>
