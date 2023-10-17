<?php
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\MyTemplate;
?>
<header class="l-head3 l-head3--<?=MyTemplate::getInstance()->getView()?><?php if (\Redsign\Master\PageUtils::isHome()) { echo ' is-page-home'; } ?>"  data-flyhead-start="true">
    <div class="container">
        <div class="l-head3__head">
            <div class="l-head3__logo">
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
            <div class="l-head3__contacts">
                <a class="b-head-icon b-head-icon--fill" href="<?=SITE_DIR?>contacts/">
                    <svg class="icon-svg icon-svg-location"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-location"></use></svg>
                </a>
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/header/phone.php' ?>
                <a href="<?=SITE_DIR?>include/forms/ask/" data-type="ajax" class="b-head-ask">
                    <span class="b-head-icon hidden-lg hidden-md">
                        <svg class="icon-svg icon-svg-message"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-message"></use></svg>
                    </span>
                    <span class="b-head-ask__text btn btn-default">
                        <?=Loc::getMessage('RS_ASK_BUTTON');?>
                    </span>
                </a>
                <span class="l-head3__cart">
                    <?=$APPLICATION->ShowViewContent('rs_mobile_cart');?>
                </span>
            </div>
        </div>
    </div>
    <div class="l-head3__menu-search">
        <div class="l-head3__menu">
        <?$APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => SITE_DIR.'include/header/vertical_menu.php'
            )
        ); ?>
        </div>
        <div class="l-head3__search-box">
            <div class="l-head3__search">
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_DIR.'/include/header/search.php'
                    )
                ); ?>
            </div>
        </div>
        <?php if (\Redsign\Master\PageUtils::isHome()): ?>
        <div class="l-head3__banner">
            <?$APPLICATION->IncludeComponent(
                  'bitrix:main.include',
                  '',
                  array(
                      'AREA_FILE_SHOW' => 'file',
                      'PATH' => SITE_DIR.'include/index/main_banners.php',
                      'RS_BANNER_TYPE' => 'wide',
                  )
              ); ?>
        </div>
        <?php endif; ?>
    </div>
</header>
