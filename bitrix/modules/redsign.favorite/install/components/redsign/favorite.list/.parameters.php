<?php

/**
 * @var array $arCurrentValues
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


$arComponentParameters = array(
    'PARAMETERS' => array(
        'ACTION_VARIABLE' => array(
            'NAME' => GetMessage('ACTION_VARIABLE'),
            'DEFAULT' => 'action',
        ),
        'PRODUCT_ID_VARIABLE' => array(
            'NAME' => GetMessage('PRODUCT_ID_VARIABLE'),
            'DEFAULT' => 'id',
        ),
    ),
);
