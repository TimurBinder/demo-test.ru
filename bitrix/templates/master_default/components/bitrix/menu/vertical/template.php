<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.menu-aim/jquery.menu-aim.js');

if (count($arResult) < 1) {
  return;
}

$isMoreItems = false;
$firstLvlItems = 0;
foreach ($arResult as $arItem) {
    if ($arItem['DEPTH_LEVEL'] == 1) {
        $firstLvlItems++;
        if ($firstLvlItems > 9) {
            $isMoreItems = true;
            break;
        }
    }
}

?>
<div class="navbar navbar-default b-vertical-menu">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle b-vertical-menu__toggle" data-toggle="collapse" data-target="#bs-navbar-mainmenu">
            <span class="b-vertical-menu__toggle-menu"><?=Loc::getMessage('RS.MASTER.MENU_VERTICAL.MENU');?></span>
            <span class="b-vertical-menu__toggle-icon">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </span>
        </button>
    </div>
    <div class="navbar-collapse collapse b-vertical-menu__navbar js-menu<?php if ($isMoreItems) echo ' is-more-items'?>" data-type="<?=$arParams['MENU_TYPE']?>" id="bs-navbar-mainmenu" aria-expanded="false" style="height: 1px;">
        <ul class="nav navbar-nav b-vertical-menu__nav js-menu__nav">
            <?php
            $previousLevel = 0;
            foreach ($arResult as $arItem):
            ?>
                <?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
                    <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
                <?php endif; ?>

                <?php if ($arItem["IS_PARENT"]): ?>
                    <li class="dropdown">
                        <a href="<?=$arItem['LINK']?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?=$arItem['TEXT']?><span class="b-vertical-menu__plus js-menu__plus"></span></a>
                        <ul class="dropdown-menu" role="menu">
                <?php else: ?>
                  <li><a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a></li>
                <?php endif; ?>
            <?php $previousLevel = $arItem["DEPTH_LEVEL"]; endforeach; ?>

            <?php if ($previousLevel > 1): ?>
              <?=str_repeat("</ul></li>", ($previousLevel-1) );?>
            <?php endif; ?>
            <li class="b-vertical-menu__more"><a href="#"></a></li>
        </ul>
    </div>
</div>
<?php $this->SetViewTarget('rs_dl_menu'); ?>
<div class="b-dl-menu">
<?php
$previousLevel = 0;
foreach ($arResult as $arItem):
?>
    <?php if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel): ?>
        <?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
    <?php endif; ?>

    <?php if ($arItem["IS_PARENT"]): ?>
        <li class="b-dl-menu__item has-subitems">
            <a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a>
            <ul class="b-dl-menu__subitems">
                <li class="b-dl-menu__item b-dl-menu__item--back"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link is-back"><?=Loc::getMessage('RS.MASTER.MENU_VERTICAL.BACK');?></a></li>
                <li class="b-dl-menu__item b-dl-menu__item--main"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a></li>
    <?php else: ?>
      <li class="b-dl-menu__item"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a></li>
    <?php endif; ?>
<?php $previousLevel = $arItem["DEPTH_LEVEL"]; endforeach; ?>

<?php if ($previousLevel > 1): str_repeat("</ul></li>", ($previousLevel-1) );
endif;
?>
</div>
<? $this->EndViewTarget(); ?>
