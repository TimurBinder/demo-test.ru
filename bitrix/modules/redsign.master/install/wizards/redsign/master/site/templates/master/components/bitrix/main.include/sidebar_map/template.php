<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
?>
<a href="<?=$arParams['RS_LINK']?>" class="b-sidebar-map" style="background-image:url('<?=SITE_DIR?>include/sidebar/map.jpg')">
    <span class="b-sidebar-map__content">
        <span class="b-sidebar-map__icon"><svg class="icon-svg"><use xlink:href="#svg-location"></use></svg></span>
        <?php
        if($arResult["FILE"] <> '') {
          include($arResult["FILE"]);
        }
        ?>
    </span>
</a>
