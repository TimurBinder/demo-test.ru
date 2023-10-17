<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

if (count($arResult) < 1) {
    return;
}
?>
<ul class="dropdown-menu">
    <?php foreach ($arResult as $index => $arItem): ?>
        <li><a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a></li>
    <?php endforeach; ?>   
</ul>
