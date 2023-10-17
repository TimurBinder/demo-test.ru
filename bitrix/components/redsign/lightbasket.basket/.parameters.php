<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * @var array $arComponentParameters
 * @var array $arCurrentValues
 * @var string $componentPath
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


if (!Loader::includeModule('iblock')) {
    return false;
}

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int) $arCurrentValues['IBLOCK_ID'] > 0);

$arIBlock = array();
$iblockFilter = (
    !empty($arCurrentValues['IBLOCK_TYPE'])
    ? array('=IBLOCK_TYPE_ID' => $arCurrentValues['IBLOCK_TYPE'], '=ACTIVE' => 'Y')
    : array('=ACTIVE' => 'Y')
);
$rsIBlock = \Bitrix\Iblock\IblockTable::getList(array(
    'filter' => $iblockFilter,
));
while ($arr = $rsIBlock->fetch()) {
    $arIBlock[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

$arProperty = array();
if ($iblockExists) {
    $propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
        'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
        'filter' => array('=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], '=ACTIVE' => 'Y'),
        'order' => array('SORT' => 'ASC', 'NAME' => 'ASC'),
    ));

    while ($property = $propertyIterator->fetch()) {
        $propertyCode = (string) $property['CODE'];
        if ($propertyCode == '') {
            $propertyCode = $property['ID'];
        }
        $arProperty[$propertyCode] = '[' . $propertyCode . '] ' . $property['NAME'];
    }
    unset($propertyCode, $propertyName, $property, $propertyIterator);
}

$arComponentParameters = array(
    'PARAMETERS' => array(
        'IBLOCK_TYPE' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('IBLOCK_TYPE'), //'RLBB_IBLOCK_ID',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('IBLOCK_IBLOCK'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlock,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ),
        'AJAX_MODE' => array(),
        'PROPS' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('PROPS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperty,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y',
        ),
        'PATH_TO_ORDER' => array(
            'PARENT' => 'URL_TEMPLATES',
            'NAME' => Loc::getMessage('PATH_TO_ORDER'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/cart/order/',
        ),
    ),
);
