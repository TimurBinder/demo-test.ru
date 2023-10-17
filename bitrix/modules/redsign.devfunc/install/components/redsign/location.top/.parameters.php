<?php

use Bitrix\Main\Localization\Loc;

/**
 * @var array $arComponentParameters
 * @var array $arCurrentValues
 * @var string $componentPath
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentParameters = array(
    'GROUPS' => array(),
    'PARAMETERS' => array(
        'COUNT_ITEMS' => array(
            'NAME' => Loc::getMessage('RS_LOCATION_TOP_PARAMETERS_COUNT_ITEMS'),
            "PARENT" => "BASE",
            'TYPE' => 'STRING',
            'DEFAULT' => 15
        )
    ),
);
