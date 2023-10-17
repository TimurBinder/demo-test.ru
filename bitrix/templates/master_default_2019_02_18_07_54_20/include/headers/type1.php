<?php
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\MyTemplate;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;

$isHideAskButton = Option::get('redsign.master', 'head_settings_show_ask_button') != 'Y';
$isEnableTopline = Option::get('redsign.master', 'head_settings_show_topline') == 'Y';
$sContactsPath = Option::get('redsign.master', 'head_settings_contacts_path');
?>
<header class="l-head1 l-head1--<?=MyTemplate::getInstance()->getView()?>">
    <?php
    if ($isEnableTopline) {
        include  Application::getDocumentRoot().SITE_TEMPLATE_PATH.'/include/headers/topline1.php';
    }
    ?>
    <div class="container">
        <div class="l-head1__head">
            
            <div class="l-head1__logo">
                <div class="b-head1-logo">
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
                    <span class="b-head1-logo__slogan">
                        <?$APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '',
                            array(
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/slogan.php',
                            )
                        ); ?>
                    </span>
                </div>
            </div>
            <div class="l-head1__contacts">
                <a class="b-head-icon b-head-icon--fill" href="<?=$sContactsPath?>">
                    <svg class="icon-svg icon-svg-location"><use xlink:href="#svg-location"></use></svg>
                </a>
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/socials.php'; ?>
            </div>
            <div class="l-head1__recall">
                <span class="b-head1-contacts">
                    <a class="b-head-icon" href="<?=$sContactsPath?>">
                        <svg class="icon-svg icon-svg-location"><use xlink:href="#svg-location"></use></svg>
                    </a>
                </span>
                <?php if (!$isHideAskButton): ?>
                <a href="<?=SITE_DIR?>include/forms/ask/" class="b-head-ask b-head-ask--dotted" data-type="ajax" title="<?=Loc::getMessage('RS_ASK_BUTTON');?>">
                    <span class="b-head-icon hidden-lg hidden-md">
                        <svg class="icon-svg icon-svg-message"><use xlink:href="#svg-message"></use></svg>
                    </span>
                    <span class="b-head-ask__text">
                        <?=Loc::getMessage('RS_ASK_BUTTON');?>
                    </span>
                </a>
                <?php endif; ?>
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/phone.php' ?>
            </div>
            <div class="l-head1__cart">
                <?=$APPLICATION->ShowViewContent('rs_mobile_cart');?>
            </div>
        </div>
    </div>
    <div class="l-head1__menu" data-flyhead-start="true">
        <?$APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/header/horizontal_menu.php'
            )
        ); ?>
        <div class="l-head1__search">
            <?$APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                array(
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_DIR.'include/header/search.php'
                )
            ); ?>
        </div>
    </div>
    <?php if (\Redsign\Master\PageUtils::isHome()): ?>
        <?$APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/index/main_banners.php',
                'RS_BANNER_TYPE' => 'wide',
            )
        ); ?>
    <?php endif; ?>
</header>
