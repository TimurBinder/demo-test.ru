<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

if (count($arResult) < 1) {
  return;
}
?>
<ul class="b-footer-menu<?php if ($arParams['RS_MARK_ITEM'] == 'ALL') { echo ' b-footer-menu--mark-all';} else if ($arParams['RS_MARK_ITEM'] == 'FIRST') {echo ' b-footer-menu--mark-first'; }?>">
    <?php foreach ($arResult as $index => $arItem): ?>
    <li class="b-footer-menu__item <?php if ($arItem['DEPTH_LEVEL'] == 1) echo ' is-mark'?>">
        <a href="<?=$arItem['LINK']?>" class="b-footer-menu__link"><?=$arItem['TEXT']?></a>
    </li>
    <?php endforeach; ?>
</ul>
