<?php

namespace Redsign\Master;

class PageUtils {

    public static function isHome() {
        global $APPLICATION;

        $arHomePages = array(
            SITE_DIR.'index.php',
            SITE_DIR.'index2.php',
            SITE_DIR.'index3.php',
            SITE_DIR.'index4.php',
            SITE_DIR.'index5.php',
            SITE_DIR.'index6.php',
        );

        return in_array($APPLICATION->GetCurPage(true), $arHomePages);
    }

}
