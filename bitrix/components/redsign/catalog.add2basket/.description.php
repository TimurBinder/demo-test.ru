<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_NAME_ADD2BASKET'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCR_ADD2BASKET'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS_ADD2BASKET'),
        'CHILD' => array(
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM_ADD2BASKET'),
            'SORT' => 8000,
        ),
    ),
);
