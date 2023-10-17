<?php

use Bitrix\Main\Localization\Loc;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentDescription = array(
    'NAME' => Loc::getMessage('RS.TUNING.NAME'),
    'DESCRIPTION' => Loc::getMessage('RS.TUNING.DESCRIPTION'),
    'ICON' => '',
    'PATH' => array(
        'ID' => 'redsign',
        'SORT' => 2000,
        'NAME' => Loc::getMessage('RS.TUNING.PATH_NAME_REDSIGN'),
        'CHILD' => array(
            'ID' => 'tuning',
            'NAME' => Loc::getMessage('RS.TUNING.NAMEPATH_NAME_TUNING'),
            'SORT' => 8000,
        ),
    ),
);
