<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('ALFA_COM_HCARD_NAME'),
    'DESCRIPTION' => Loc::getMessage('ALFA_COM_HCARD_DESCR'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
    ),
);
