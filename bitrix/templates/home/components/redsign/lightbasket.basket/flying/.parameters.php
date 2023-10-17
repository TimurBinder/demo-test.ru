<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateParameters = array(
    'PATH_TO_CART' => array(
        'NAME' => Loc::getMessage('RS_LIGHTBASKET_PATH_TO_CART'),
        'TYPE' => 'SRTING',
        'DEFAULT' => '/cart/'
    ),
    'PATH_TO_ORDER' => array(
        'NAME' => Loc::getMessage('RS_LIGHTBASKET_PATH_TO_ORDER'),
        'TYPE' => 'SRTING',
        'DEFAULT' => '/cart/'
    ),
    'PATH_TO_CATALOG' => array(
        'NAME' => Loc::getMessage('RS_LIGHTBASKET_PATH_TO_CATALOG'),
        'TYPE' => 'SRTING',
        'DEFAULT' => '/catalog/'
    )
);
