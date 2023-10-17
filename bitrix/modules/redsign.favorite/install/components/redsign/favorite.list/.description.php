<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('RS_FAVORITE_COM_NAME'),
    'DESCRIPTION' => Loc::getMessage('RS_FAVORITE_DESCRIPTION'),
    'SORT' => 10,
    'CACHE_PATH' => 'Y',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('ALFA_COM_COMPONENTS'),
        'CHILD' => array(
            'ID' => 'favorite',
            'NAME' => Loc::getMessage('ALFA_FAVORITE_COM_SEC_NAME'),
            'SORT' => 10,
        )
    ),
);
