<?php
$arProp = $arItem["DISPLAY_PROPERTIES"];

if (!empty($arProp["PROP_GALLERY_1"]["VALUE"]))
    $pictures = $arProp["PROP_GALLERY_1"]["FILE_VALUE"];
else
    $pictures = $arResult["ELEMENT"]["PICTURES"]["IMAGES"];

if (count($pictures) > 0):
?>
    <div class="container">
        <div class="landing__title landing__title__padding">
            <?php if ($name != ""):?>
                <h3><?=$name?></h3>
            <?php endif;?>
        </div>
    </div>
    <div class="landing__gallery b-light-gallery js-light-gallery">
        <div class="b-light-gallery__items owl owl-carousel owl-theme <?=(count($pictures) > 0 ? 'landing__gallery__center' : '')?>" data-slider="true" data-slider-options='<?=\Bitrix\Main\Web\Json::encode($arParams['OWL_PARAMS'])?>'>
            <?php foreach ($pictures as $pic):?>
                <div class="landing__gallery__item b-light-gallery__item">
                    <a href="<?=$pic['SRC']?>" data-fancybox="js-light-gallery__<?=$arItem['ID']?>" class="landing__gallery__link js-light-gallery__<?=$arItem['ID']?>">
                        <span class="landing__gallery__img" style="background-image:url('<?=$pic['SRC']?>')"></span>
                    </a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
<?php endif;?>
