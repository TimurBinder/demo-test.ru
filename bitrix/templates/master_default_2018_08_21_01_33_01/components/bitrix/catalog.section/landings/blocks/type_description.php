<?php $arProp = $arItem["DISPLAY_PROPERTIES"];?>
<div class="container">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>

    <div class="landing__center" <?=($arProp["PROP_DESCR_1"]["VALUE"] != "" ? 'style="max-width:'.$arProp["PROP_DESCR_1"]["VALUE"].'px"' : '');?>>

        <?php if ($arItem["DETAIL_TEXT"] != ""):?>
            <div class="landing__descr">
                <?=$arItem["DETAIL_TEXT"]?>
            </div>
        <?php endif;?>
    </div>
</div>
