<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = [
    'NAME' => Loc::getMessage('RS_FORMS_TEMPLATE_NAME'),
    'DESCRIPTION' => Loc::getMessage('RS_FORMS_TEMPLATE_DESCRIPTION'),
    'ICON' => '',
    'PATH' => [
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('RS_FORMS_TEMPLATE_PATH_NAME_REDSIGN'),
        'CHILD' => [
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM'),
            'SORT' => 8000,
        ],
    ],
];
