<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.menu-aim/jquery.menu-aim.js');

if (count($arResult) < 1) {
    return;
}
?>
<ul class="b-sidebar-nav">
    <?php
    $previousLevel = 0;
    foreach ($arResult as $index => $arItem):
    ?>
        <?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
            <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
        <?php endif; ?>

        <?php if ($arItem["IS_PARENT"]): ?>
            <li class="dropdown-submenu b-sidebar-nav__item<?php if ($arItem['SELECTED']) echo ' is-selected'; ?>">
                <a href="<?=$arItem['LINK']?>" class="b-sidebar-nav__link"><?=$arItem['TEXT']?><span class="b-sidebar-nav__toggle<?php if (!$arItem['SELECTED']) echo ' collapsed'; ?>" data-toggle="collapse" href="#sidebar_menu_<?=$index?>_pc"></span></a>
                <ul class="b-sidebar-nav__submenu collapse<?php if ($arItem['SELECTED']) echo ' in'; ?>" id="sidebar_menu_<?=$index?>_pc" aria-expanded="false">
        <?php else: ?>
            <li class="b-sidebar-nav__item<?php if ($arItem['SELECTED']) echo ' is-selected'; ?>"><a href="<?=$arItem['LINK']?>" class="b-sidebar-nav__link"><?=$arItem['TEXT']?></a></li>
        <?php endif; ?>
    <?php $previousLevel = $arItem["DEPTH_LEVEL"]; endforeach; ?>

    <?php if ($previousLevel > 1): ?>
        <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
    <?php endif; ?>
</ul>
