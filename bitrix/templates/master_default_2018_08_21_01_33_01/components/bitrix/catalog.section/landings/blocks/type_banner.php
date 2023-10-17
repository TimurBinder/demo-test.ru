<?php
$arProp = $arItem["DISPLAY_PROPERTIES"];
$style = "";

if ($arProp["PROP_BANNER_1"]["FILE_VALUE"]["SRC"] != "")
    $style .= "background-image:url('".$arItem["DISPLAY_PROPERTIES"]["PROP_BANNER_1"]["FILE_VALUE"]["SRC"]."');";
if ($arItem["PROPERTIES"]["PROP_BANNER_3"]["VALUE"] != "")
    $style .= "min-height:".$arItem["PROPERTIES"]["PROP_BANNER_3"]["VALUE"]."px;";
?>
<div class="landing__banner" style="<?=$style?>">
    <div class="container landing__banner__contain">
        <div class="h2 landing__banner__title"><?echo $arItem["PROPERTIES"]["BLOCK_NAME"]["VALUE"]?></div>
        <div class="landing__banner__descr"><?echo $arProp["PROP_BANNER_2"]["DISPLAY_VALUE"]?></div>
    </div>
</div>
