<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('RS_COMPONENT_LOCATION_MAIN_NAME'),
    'DESCRIPTION' => Loc::getMessage('RS_COMPONENT_LOCATION_MAIN_DESC'),
    'ICON' => '',
    'CACHE_PATH' => 'Y',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 5000,
        'NAME' => Loc::getMessage('RS_COMPONENT_LOCATION_PATH_MAIN_NAME'),
    ),
);
