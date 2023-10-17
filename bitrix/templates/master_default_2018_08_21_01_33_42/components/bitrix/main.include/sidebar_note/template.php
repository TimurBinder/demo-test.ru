<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
?>
<div class="b-sidebar-note">
    <?php
    if($arResult["FILE"] <> '') {
      include($arResult["FILE"]);
    }
  ?>
</div>
