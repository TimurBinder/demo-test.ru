<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = [
    'NAME' => Loc::getMessage('ALFA_COM_NAME'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCRIPTION'),
    'SORT' => 10,
    'CACHE_PATH' => 'Y',
    'PATH' => [
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS'),
        'CHILD' => [
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM'),
            'SORT' => 8000,
        ],
    ],
];
