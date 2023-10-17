<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateParameters['RS_MARK_ITEM'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MENU_PARAMETERS_MARK_ITEM'),
    'TYPE' => 'LIST',
    'DEFAULT' => '',
    'MULTIPLE' => 'N',
    'VALUES' => array(
        'NONE' => Loc::getMessage('RS.MENU_PARAMETERS_MARK_ITEM_NONE'),
        'FIRST' => Loc::getMessage('RS.MENU_PARAMETERS_MARK_ITEM_FIRST'),
        'ALL' => Loc::getMessage('RS.MENU_PARAMETERS_MARK_ITEM_ALL')
    )
);
