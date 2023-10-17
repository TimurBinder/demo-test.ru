<?php
use \Bitrix\Main\Localization\loc;
use \Redsign\Master\MyTemplate;
?>
<footer class="l-footer l-footer--container l-footer--<?=MyTemplate::getInstance()->getView()?>">
    <div class="l-footer__inner">
        <div class="l-footer__main">
            <div class="b-footer-contacts">
                <a href="<?=SITE_DIR.'contacts/'?>" class="b-footer-contacts__title"><?=Loc::getMessage('RS.FOOTER_CONTACTS');?></a>
                <div class="b-footer-contacts__phones">
                    <div class="b-footer-contacts__phones-icon"><svg class="icon-svg"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-device-mobile"></use></svg></div>
                    <?$APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '',
                        array(
                            'AREA_FILE_SHOW' => 'file',
                            'PATH' => SITE_DIR.'include/footer/phones.php',
                        )
                    ); ?>
                </div>
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_DIR.'include/footer/contacts.php',
                    )
                ); ?>
            </div>
            <div class="l-footer__society">
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/footer/subscibe.php'; ?>
                <?php include $_SERVER['DOCUMENT_ROOT'].SITE_DIR.'include/footer/socials.php'; ?>
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_DIR.'include/footer/socials_area.php',
                    )
                ); ?>

            </div>
            <div class="l-footer__menus">
                <?$APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'PATH' => SITE_DIR.'include/footer/menus.php',
                    )
                ); ?>
            </div>
        </div>
    </div>
    <div class="l-footer__hr"></div>
    <div class="l-footer__copyright">
        <div class="l-footer__allrights">
            <?$APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                array(
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_DIR.'include/footer/allrights.php',
                )
            ); ?>
        </div>
        <div class="l-footer__icons">
            <?$APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                array(
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_DIR.'include/footer/icons.php',
                )
            ); ?>
        </div>
        <?php #REDSIGN_COPYRIGHT# ?>
        <div class="l-footer__alfasystems"><?=Loc::getMessage('RS.FOOTER_COPYRIGHT');?></div>
    </div>
</footer>
