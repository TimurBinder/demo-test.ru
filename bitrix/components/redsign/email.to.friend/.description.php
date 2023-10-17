<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_NAME__SEND_EMAIL'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCR__SEND_EMAIL'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS__SEND_EMAIL'),
        'CHILD' => array(
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM__SEND_EMAIL'),
            'SORT' => 8000,
        ),
    ),
);
