<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);

$this->addExternalJs(SITE_TEMPLATE_PATH.'/assets/vendor/jquery.menu-aim/jquery.menu-aim.js');

$arParams['FULL_MENU_MAX_COUNT'] = isset($arParams['FULL_MENU_MAX_COUNT']) ? $arParams['FULL_MENU_MAX_COUNT'] : 6;

if (!function_exists('getMenuItems')) {
    function getMenuItems($arItems, &$arParams) {
        global $APPLICATION;
        foreach ($arItems as $arItem) {
            $sMenuItemType = 'DEFAULT';

            $hasSubItems = (bool) $arItem["IS_PARENT"];

            if ($arItem['DEPTH_LEVEL'] == 1) {
                $sMenuItemType = (isset($arItem['PARAMS']['TYPE']) ? $arItem['PARAMS']['TYPE'] : 'DEFAULT');
                $arParams['ROOT_ITEM_TYPE'] = $sMenuItemType;
            }

            if ($arItem['DEPTH_LEVEL'] == 2) {
                $arParams['COUNT'] = 0;
                $arParams['HIDDEN'] = false;
                $arParams['HIDDEN_ITEMS'] = 0;
                $arParams['PARENT_MENU_ITEM'] = &$arItem;
            } else if ($arItem['DEPTH_LEVEL'] > 2) {
                $arParams['COUNT']++;
            }

            if ($arParams['ROOT_ITEM_TYPE'] == 'FULL') {
                if ($arParams['HIDDEN'] && $arItem['DEPTH_LEVEL'] > 2) {
                    $arParams['HIDDEN_ITEMS']++;
                } else if ($arParams['COUNT'] > $arParams['FULL_MENU_MAX_COUNT']) {
                    $arParams['HIDDEN'] = true;
                    $arParams['HIDDEN_ITEMS']++;
                }
            }
            ?>
            <?php if ($hasSubItems): ?>
            <li class="dropdown b-horizontal-menu__item<?php if ($sMenuItemType == 'FULL'): ?> is-full-menu<?php endif; ?><?php if ($arParams['HIDDEN']): ?> is-hidden<?php endif; ?>">
                <?php if ($arItem['DEPTH_LEVEL'] == 2 && $arItem['PICTURE']): ?>
                <a href="<?=$arItem['LINK']?>" class="b-horizontal-menu__picture">
                    <img src="<?=$arItem['PICTURE']['SRC']?>" alt="<?=$arItem['TEXT']?>" name="<?=$arItem['TEXT']?>">
                </a>
                <?php endif; ?>
                <a href="<?=$arItem['LINK']?>" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?=$arItem['TEXT']?><span class="b-horizontal-menu__plus js-menu__plus"></span></a>

                <?php if ($sMenuItemType == 'FULL'): ?>
                <div class="b-horizontal-menu__full-items">
                    <ul class="dropdown-menu" role="menu">
                    <?php getMenuItems($arItem['SUB_ITEMS'], $arParams); ?>
                    </ul>
                    <?php if (isset($arItem['PARAMS']['INCLUDE_FILE']) && file_exists($_SERVER['DOCUMENT_ROOT'].$arItem['PARAMS']['INCLUDE_FILE'])): ?>
                    <div class="b-horizontal-menu__include" >
                        <?php $APPLICATION->IncludeComponent(
                            "bitrix:main.include",
                            "",
                            array(
                                "AREA_FILE_SHOW" => "file",
                                "PATH" => $arItem['PARAMS']['INCLUDE_FILE'],
                                "EDIT_TEMPLATE" => ""
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );?>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($arItem['PARAMS']['IMAGE_FILE'])): ?>
                    <a href="<?=$arItem['PARAMS']['IMAGE_LINK']?>" target="_blank" class="b-horizontal-menu-full-img"><img src="<?=$arItem['PARAMS']['IMAGE_FILE']?>" alt="<?=$arItem['TEXT']?>"<?php if (isset($arItem['PARAMS']['IMAGE_STYLES'])) { echo 'style="'.$arItem['PARAMS']['IMAGE_STYLES'].'"'; } ?>></a>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                  <ul class="dropdown-menu" role="menu">
                      <?php getMenuItems($arItem['SUB_ITEMS'], $arParams); ?>
                      <?php if ($arItem['DEPTH_LEVEL'] == 2 && $arParams['HIDDEN']) {
                          ?><li class="b-horizontal-menu__item is-more-link"><a href="<?=$arParams['PARENT_MENU_ITEM']['LINK']?>"><?=Loc::getMessage('RS.MASTER.MENU_HORIZONTAL.HIDDEN_ITEMS'); ?> <?=$arParams['HIDDEN_ITEMS'] ?></a></li><?
                      }
                      ?>
                  </ul>
                <?php endif; ?>
            </li>
            <?php else: ?>
            <li class="b-horizontal-menu__item<?php if ($arParams['HIDDEN']): ?> is-hidden<?php endif; ?>">
                <?php if ($arItem['DEPTH_LEVEL'] == 2 && $arItem['PICTURE']): ?>
                <a href="<?=$arItem['LINK']?>" class="b-horizontal-menu__picture">
                    <img src="<?=$arItem['PICTURE']['SRC']?>" alt="<?=$arItem['TEXT']?>" name="<?=$arItem['TEXT']?>">
                </a>
                <?php endif; ?>
                <a href="<?=$arItem['LINK']?>"><?=$arItem['TEXT']?></a>
            </li>
            <?php endif; ?>
            <?php
        }
    }
}

if (!function_exists('getDLMenuItems')) {
    function getDLMenuItems($arItems) {
        foreach ($arItems as $arItem) {
        $hasSubItems = (bool) $arItem['IS_PARENT'];
        ?>
            <li class="b-dl-menu__item<?php if ($hasSubItems): ?> has-subitems<?php endif; ?>">
                <a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a>
                <?php if ($hasSubItems): ?>
                <ul class="b-dl-menu__subitems">
                    <li class="b-dl-menu__item b-dl-menu__item--back"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link is-back"><?=Loc::getMessage('RS.MASTER.MENU_HORIZONTAL.BACK');?></a></li>
                    <li class="b-dl-menu__item b-dl-menu__item--main"><a href="<?=$arItem['LINK']?>" class="b-dl-menu__link"><?=$arItem['TEXT']?></a></li>
                    <?php getDLMenuItems($arItem['SUB_ITEMS']); ?>
                </ul>
                <?php endif; ?>
            </li>
        <?php
        }
    }
}


if (count($arResult) < 1) {
  return;
}
?>
<div class="navbar navbar-default b-horizontal-menu">
    <div class="navbar-header">
        <div class="container">
            <button type="button" class="navbar-toggle b-horizontal-menu__toggle" data-toggle="collapse" data-target="#bs-navbar-mainmenu">
                <span class="b-horizontal-menu__toggle-menu"><?=Loc::getMessage('RS.MASTER.MENU_HORIZONTAL.MENU');?></span>
                <span class="b-horizontal-menu__toggle-icon">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="container">
        <div class="navbar-collapse collapse b-horizontal-menu__navbar js-menu" data-type="<?=$arParams['MENU_TYPE']?>" id="bs-navbar-mainmenu" aria-expanded="false" style="height: 1px;">
            <ul class="nav navbar-nav b-horizontal-menu__nav js-menu__nav">
                <?=getMenuItems($arResult, $arParams);?>
                <li class="dropdown js-menu__more b-horizontal-menu__more b-horizontal-menu__item">
                    <a href="#" class="dropdown-toggle b-horizontal-menu__more-btn js-menu__more-btn" data-toggle="dropdown" role="button" aria-expanded="false"></a>
                    <ul class="dropdown-menu js-menu__more-items" role="menu"></ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php $this->SetViewTarget('rs_dl_menu'); ?>
<div class="b-dl-menu">
<?php getDLMenuItems($arResult); ?>
</div>
<?php $this->EndViewTarget(); ?>
