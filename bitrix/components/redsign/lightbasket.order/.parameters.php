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
$catalogIblockExists = (!empty($arCurrentValues['CATALOG_IBLOCK_ID']) && (int) $arCurrentValues['CATALOG_IBLOCK_ID'] > 0);

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

$arCatalogIBlock = array();
$iblockFilter = (
    !empty($arCurrentValues['CATALOG_IBLOCK_TYPE'])
    ? array('=IBLOCK_TYPE_ID' => $arCurrentValues['CATALOG_IBLOCK_TYPE'], '=ACTIVE' => 'Y')
    : array('=ACTIVE' => 'Y')
);
$rsIBlock = \Bitrix\Iblock\IblockTable::getList(array(
    'filter' => $iblockFilter,
));
while ($arr = $rsIBlock->fetch()) {
    $arCatalogIBlock[$arr['ID']] = '[' . $arr['ID'] . '] ' . $arr['NAME'];
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

$arCatalogProperty = array();
if ($catalogIblockExists) {
    $propertyIterator = \Bitrix\Iblock\PropertyTable::getList(array(
        'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE'),
        'filter' => array('=IBLOCK_ID' => $arCurrentValues['CATALOG_IBLOCK_ID'], '=ACTIVE' => 'Y'),
        'order' => array('SORT' => 'ASC', 'NAME' => 'ASC'),
    ));

    while ($property = $propertyIterator->fetch()) {
        $propertyCode = (string) $property['CODE'];
        if ($propertyCode == '') {
            $propertyCode = $property['ID'];
        }
        $arCatalogProperty[$propertyCode] = '[' . $propertyCode . '] ' . $property['NAME'];
    }
    unset($propertyCode, $propertyName, $property, $propertyIterator);
}

$arComponentParameters = array(
    'PARAMETERS' => array(
        "USER_CONSENT" => array(),
        'IBLOCK_TYPE' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('IBLOCK_TYPE'), //'RLBB_IBLOCK_ID',
            'TYPE' => 'LIST',
            'VALUES' => $arIBlockType,
            'REFRESH' => 'Y',
        ),
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('IBLOCK_IBLOCK'),
            'TYPE' => 'LIST',
            'VALUES' => $arIBlock,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ),
        'ITEMS_PROP' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('ITEMS_PROP'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperty,
        ),
        'FIELDS_PROPS' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('FIELDS_PROPS'),
            'TYPE' => 'LIST',
            'VALUES' => $arProperty,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y',
        ),
        'PATH_TO_CART' => array(
            'PARENT' => 'BASE',
            'NAME' => Loc::getMessage('PATH_TO_CART'),
            'TYPE' => 'STRING',
            'DEFAULT' => '/cart/',
        ),
        'CATALOG_IBLOCK_TYPE' => array(
          'PARENT' => 'DATA_SOURCE',
          'NAME' => Loc::getMessage('CATALOG_IBLOCK_TYPE'),
          'TYPE' => 'LIST',
          'VALUES' => $arIBlockType,
          'REFRESH' => 'Y',
        ),
        'CATALOG_IBLOCK_ID' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('CATALOG_IBLOCK_IBLOCK'),
            'TYPE' => 'LIST',
            'VALUES' => $arCatalogIBlock,
            'ADDITIONAL_VALUES' => 'Y',
            'REFRESH' => 'Y',
        ),
        'CATALOG_PROPS' => array(
            'PARENT' => 'DATA_SOURCE',
            'NAME' => Loc::getMessage('CATALOG_PROPS'),
            'TYPE' => 'LIST',
            'VALUES' => $arCatalogProperty,
            'ADDITIONAL_VALUES' => 'Y',
            'MULTIPLE' => 'Y',
            'REFRESH' => 'Y',
        ),
        'SHOW_CONFIRM' => array(
            'PARENT' => 'VISUAL',
            'NAME' => Loc::getMessage('SHOW_CONFIRM'),
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'N',
            'REFRESH' => 'Y'
        )
    ),
);


if ($arCurrentValues['SHOW_CONFIRM'] == 'Y') {
    $arComponentParameters['PARAMETERS']['CONFIRM_TEXT'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('CONFIRM_TEXT'),
        'TYPE' => 'STRING'
    );
}
