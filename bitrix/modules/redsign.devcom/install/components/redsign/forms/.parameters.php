<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arCurrentValues
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


Loc::loadMessages(__FILE__);

if (!Loader::includeModule('iblock'))
    return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = (
    !empty($arCurrentValues['IBLOCK_TYPE'])
    ? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
    : array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
    $arIBlock[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

$arComponentParameters = array(
    'PARAMETERS' => array(
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('IBLOCK_TYPE'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('IBLOCK_ID'),
            'TYPE' => 'LIST',
            'ADDITIONAL_VALUES' => 'Y',
            'VALUES' => $arIBlock,
            'REFRESH' => 'Y',
        ),
        'AJAX_MODE' => array(),
        'SUCCESS_MESSAGE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('RS_FORMS_SUCCESS_MESSAGE_PARAMETER'),
            'TYPE' => 'STRING'
        ),
        'EVENT_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('RS_FORMS_EVENT_TYPE_PARAMETER'),
            'TYPE' => 'SRTING',
        ),
        'EMAIL_TO' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('RS_FORMS_EMAIL_TO_PARAMETER'),
            'TYPE' => 'STRING'
        ),
        'USE_CAPTCHA' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('USE_CAPTCHA'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT_VALUE' => 'Y',
        ),
        "USER_CONSENT" => array(),
    ),
);
