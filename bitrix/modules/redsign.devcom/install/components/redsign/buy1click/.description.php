<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_NAME__BIY1CLICK'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_DESCR__BIY1CLICK'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS__BIY1CLICK'),
        'CHILD' => array(
            'ID' => 'devcom',
            'NAME' => Loc::getMessage('ALFA_COM_DEV_COM__BIY1CLICK'),
            'SORT' => 8000,
        ),
    ),
);
