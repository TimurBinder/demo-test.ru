<?php
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\MyTemplate;
use \Bitrix\Main\Application;
use \Bitrix\Main\Config\Option;

$isHideAskButton = Option::get('redsign.master', 'head_settings_show_ask_button') != 'Y';
$isEnableTopline = Option::get('redsign.master', 'head_settings_show_topline') == 'Y';
$sContactsPath = Option::get('redsign.master', 'head_settings_contacts_path');
?>
<header class="l-head2 l-head2--<?=MyTemplate::getInstance()->getView()?>">
    <div class="container">
        <?php
        if ($isEnableTopline) {
            include  Application::getDocumentRoot().SITE_TEMPLATE_PATH.'/include/headers/topline1.php';
        }
        ?>
        <div class="l-head2__head">
            <div class="l-head2__logo">
                <div class="b-head2-logo">
                    <a class="b-head2-logo__name" href="<?=SITE_DIR?>">
                        <?$APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '',
                            array(
                                'AREA_FILE_SHOW' => 'file',
                                'PATH' => SITE_DIR.'include/header/logo.php',
                            )
                        ); ?>
                    </a>
                    <span class="b-head2-logo__slogan">
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
            <div class="l-head2__contacts">
                <a class="b-head-icon b-head-icon--fill" href="<?=$sContactsPath?>">
                    <svg class="icon-svg icon-svg-location"><use xlink:href="#svg-location"></use></svg>
                </a>
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/phone.php' ?>
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

                <span class="l-head2__cart">
                    <?=$APPLICATION->ShowViewContent('rs_mobile_cart');?>
                </span>
            </div>
        </div>
    </div>
    <div class="l-head2__menu" data-flyhead-start="true">
        <?$APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/header/horizontal_menu.php',
            )
        ); ?>
        <div class="l-head2__search">
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
            'RS_BANNER_TYPE' => 'center',
        )); ?>
      <?php endif; ?>
</header>
