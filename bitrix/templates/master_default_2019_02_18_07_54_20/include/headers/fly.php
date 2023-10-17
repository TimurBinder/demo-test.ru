<?php
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;

$isHideAskButton = Option::get('redsign.master', 'head_settings_hide_ask_button') == 'Y';
$sAskButtonText = Option::get('redsign.master', 'head_settings_ask_button_text');
$sAskButtonFormId = Option::get('redsign.master', 'head_settings_ask_button_b24crmform_id', '');
$sAskButtonFormSec = Option::get('redsign.master', 'head_settings_ask_button_b24crmform_sec', '');
$sContactsPath = Option::get('redsign.master', 'head_settings_contacts_path');

\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/dl_menu.js');
\Bitrix\Main\Page\Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/assets/js/fly_head.js');

\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/fly_head.css');
\Bitrix\Main\Page\Asset::getInstance()->addCss(SITE_TEMPLATE_PATH.'/assets/css/dl_menu.css');
?>
<div class="b-fly-head js-fly-head">
    <div class="b-fly-head__container">
        <div class="b-fly-head__blocks">
            <div class="b-fly-head__logo">
                <a class="b-head1-logo__name" href="<?=SITE_DIR?>">
                    <?$APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '',
                        array(
                            'AREA_FILE_SHOW' => 'file',
                            'PATH' => SITE_DIR.'include/header/logo.php',
                        )
                    ); ?>
                </a>
            </div>

            <div class="b-fly-head__slogan">
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_DIR.'include/header/slogan.php',
                    )
                ); ?>
            </div>

            <div class="b-fly-head__menu js-fly-menu">
                <a class="b-fly-head__menu-toggle js-fly-menu__toggle" href="">
                    <span class="b-fly-head__menu-toggle-text"><?=Loc::getMessage('RS.MASTER.MENU');?></span>
                    <span class="b-fly-head__menu-toggle-icon">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </span>
                </a>
                <div class="b-fly-head__menu-items js-fly-menu__items is-close">
                    <?php $APPLICATION->ShowViewContent('rs_dl_menu'); ?>
                </div>
            </div>
            <div class="b-fly-head__contacts">
                <a class="b-head-icon b-head-icon--fill" href="<?=$sContactsPath?>">
                    <svg class="icon-svg icon-svg-location"><use xlink:href="#svg-location"></use></svg>
                </a>
            </div>
            <div class="b-fly-head__phone">
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/phone.php' ?>
            </div>
            <div class="b-fly-head__btns">
                <?php if (!$isHideAskButton): ?>
                <a href="<?=SITE_DIR?>include/forms/ask/" data-type="ajax" class="b-head-ask">
                    <span class="b-head-icon hidden-lg hidden-md">
                        <svg class="icon-svg icon-svg-message"><use xlink:href="#svg-message"></use></svg>
                    </span>
                    <span class="b-head-ask__text btn btn-default">
                        <?=Loc::getMessage('RS_ASK_BUTTON');?>
                    </span>
                </a>
                <?php endif; ?>
            </div>
            <div class="b-fly-head__search">
                <?=$APPLICATION->ShowViewContent('rs_head_search'); ?>
            </div>
        </div>
    </div>
</div>
