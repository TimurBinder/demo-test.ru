<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = [
    'NAME' => Loc::getMessage('RS_LIGHTBASKET_ORDER_NAME'),
    'DESCRIPTION' => Loc::getMessage('RS_LIGHTBASKET_ORDER_DESCRIPTION'),
    'ICON' => '',
    'PATH' => [
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('RS_LIGHTBASKET_COMPONENTS_REDSIGN'),
        'CHILD' => [
            'ID' => 'lightbasket',
            'NAME' => Loc::getMessage('RS_LIGHTBASKET_COMPONENTS_LIGHTBASKET'),
            'SORT' => 8000,
        ],
    ],
];
