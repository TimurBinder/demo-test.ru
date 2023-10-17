<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_NAME__RECALL2'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCR__RECALL2'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS__RECALL2'),
        'CHILD' => array(
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM__RECALL2'),
            'SORT' => 8000,
        ),
    ),
);
