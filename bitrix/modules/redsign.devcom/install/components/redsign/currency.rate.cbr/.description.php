<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_NAME'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCRIPTION'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS'),
        'CHILD' => array(
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM'),
            'SORT' => 8000,
        ),
    ),
);
