<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Config\Option;
use \Redsign\Master;

Loc::loadMessages(__FILE__);

Loader::registerAutoLoadClasses(
    'redsign.master',
    array(
        '\Redsign\Master\ParametersUtils' => 'lib/parameters_utils.php',
        '\Redsign\Master\IblockElementExt' => 'lib/iblock_element_ext.php',
        '\Redsign\Master\SVGIconsManager' => 'lib/svg_icons_manager.php',
        '\Redsign\Master\PageUtils' => 'lib/page_utils.php',
        '\Redsign\Master\PrivacyPolicy' => 'lib/privacy_policy.php',
        '\Redsign\Master\MyTemplate' => 'lib/my_template.php',
        '\Redsign\Master\AdminUtils' => 'lib/admin_utils.php'
    )
);
