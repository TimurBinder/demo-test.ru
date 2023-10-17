<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
    'NAME' => Loc::getMessage('RS_LIGHTBASKET_BASKET_TEMPLATE_NAME'),
    'DESCRIPTION' => Loc::getMessage('RS_LIGHTBASKET_BASKET_TEMPLATE_DESCRIPTION'),
    'PATH' => array(
        'ID' => Loc::getMessage('RS_LIGHTBASKET_BASKET_TEMPLATE_PATH_ID'),
        'SORT' => '1000',
    ),
);
