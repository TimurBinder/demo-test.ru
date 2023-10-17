<?php
$arProp = $arItem["DISPLAY_PROPERTIES"];

if (!isset($arProp["PROP_DOC_1"]["FILE_VALUE"]["SRC"])):
    $count = count($arProp["PROP_PLUS_2"]["FILE_VALUE"]);
    switch ($count):

        case 1:
            $colLg = $colMd = $colSm = "6";
            $colXs = "12";
            break;
        case 2:
            $colLg = $colMd = $colSm = "6";
            $colXs = "12";
            break;
        case 3:
            $colLg = $colMd = $colSm = "4";
            $colXs = "12";
            break;
        case 4:
            $colLg = $colMd = "3";
            $colSm = "4";
            $colXs = "12";
            break;
        case 5:
            $colLg = $colMd = "1-5";
            $colSm = "6";
            $colXs = "12";
            break;
        default:
            $colLg = $colMd = "4";
            $colSm = "6";
            $colXs = "12";
            break;

    endswitch;
endif;

?>
<div class="container">
    <div class="landing__title">
        <?php if ($name != ""):?>
            <h3><?=$name?></h3>
        <?php endif;?>
    </div>
    <div class="landing__center">
        <?php include($_SERVER["DOCUMENT_ROOT"].SITE_DIR."include/template/catalog/landing_document.php"); ?>
    </div>
</div>

