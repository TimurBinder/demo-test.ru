<?php
use Bitrix\Main\Localization\Loc;

$style = "";

if ($arProp["PROP_BANNER_1"]["FILE_VALUE"]["SRC"] != "")
    $style .= "background-image:url('".$arItem["DISPLAY_PROPERTIES"]["PROP_BANNER_1"]["FILE_VALUE"]["SRC"]."');";
if ($arItem["PROPERTIES"]["PROP_BANNER_3"]["VALUE"] != "")
    $style .= "min-height:".$arItem["PROPERTIES"]["PROP_BANNER_3"]["VALUE"]."px;";

if ($arItem["PROPERTIES"]["PROP_BANNER_4"]["VALUE_XML_ID"] != "") {
    $side = $arItem["PROPERTIES"]["PROP_BANNER_4"]["VALUE_XML_ID"];
} else {
    $side = "center";
}

?>
<div class="landing__banner" style="<?=$style?>">
    <?php if ($side != "center" && $arProp['PROP_BANNER_5']['VALUE'] == 'Y'):?>
        <div class="landing__banner__shadow-<?=$side?> landing__banner__shadow-banner"></div>
    <?php endif;?>
    <div class="landing__banner__height <?=($side != 'center' ? 'container' : '');?>">
        <div class="landing__banner__contain landing__banner__contain-<?=$side?>">
            <div class="landing__banner__inner-<?=$side?> <?=($arProp['PROP_BANNER_5']['VALUE'] == 'Y' && $side == 'center' ? 'landing__banner__shadow':'')?>">
                <div class="<?=($arProp['PROP_BANNER_5']['VALUE'] == 'Y' ? 'landing__banner__darken' : '')?>">
                    <?php if ($name != ""):?>
                        <div class="h2 landing__banner__title"><?=$arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"]?></div>
                    <?php endif;?>
                    <div class="landing__banner__descr"><?=$arProp["PROP_BANNER_2"]["DISPLAY_VALUE"]?></div>
                    <?include('buttons_block.php');?>
                </div>
            </div>
        </div>
    </div>
    
</div>
