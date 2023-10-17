<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('iblock') || !Loader::includeModule('redsign.devfunc')) {
    return;
}

Loc::loadMessages(__FILE__);

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arIBlock = array();
$iblockFilter = !empty($arCurrentValues['CATALOG_IBLOCK_TYPE'])
	? array('TYPE' => $arCurrentValues['CATALOG_IBLOCK_TYPE'], 'ACTIVE' => 'Y')
	: array('ACTIVE' => 'Y');

$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
	$arIBlock[$arr['ID']] = '['.$arr['ID'].'] '.$arr['NAME'];
}
unset($arr, $rsIBlock, $iblockFilter);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['CATALOG_IBLOCK_ID']);

$arTemplateParameters['CATALOG_IBLOCK_TYPE'] = array(
    'NAME' => Loc::getMessage('IBLOCK_TYPE'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlockType,
    'REFRESH' => 'Y'
);

$arTemplateParameters['CATALOG_IBLOCK_ID'] = array(
    'NAME' => Loc::getMessage('IBLOCK_IBLOCK'),
    'TYPE' => 'LIST',
    'VALUES' => $arIBlock,
    'REFRESH' => 'Y'
);

$arTemplateParameters['CATALOG_IS_USE_CART'] = array(
    'NAME' => Loc::getMessage('RS.IS_USE_CART'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'Y'
);

$arTemplateParameters['CATALOG_PRICE_PROP'] = array(
    'NAME' => Loc::getMessage('RS.PRICE_PROP'),
  	'TYPE' => 'LIST',
  	'VALUES' => $listProp['SNL'],
);
$arTemplateParameters['CATALOG_DISCOUNT_PROP'] = array(
    'NAME' => Loc::getMessage('RS.DISCOUNT_PROP'),
  	'TYPE' => 'LIST',
  	'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['CATALOG_CURRENCY_PROP'] = array(
    'NAME' => Loc::getMessage('RS.CURRENCY_PROP'),
  	'TYPE' => 'LIST',
  	'VALUES' => $listProp['SNL'],
);

$arTemplateParameters['CATALOG_MESS_BTN_ADD_TO_BASKET'] = array(
	'NAME' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_ADD_TO_BASKET'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_ADD_TO_BASKET_DEFAULT')
);

$arTemplateParameters['CATALOG_MESS_BTN_BUY'] = array(
	'NAME' => GetMessage('CP_BCS_TPL_MESS_BTN_BUY'),
	'TYPE' => 'STRING',
	'DEFAULT' => GetMessage('CP_BCS_TPL_MESS_BTN_BUY_DEFAULT')
);


$arTemplateParameters['CATALOG_MESS_BTN_SUBSCRIBE'] = array(
	'NAME' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_SUBSCRIBE'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_SUBSCRIBE_DEFAULT')
);

$arTemplateParameters['CATALOG_MESS_BTN_DETAIL'] = array(
	'NAME' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_DETAIL'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('CP_BCS_TPL_MESS_BTN_DETAIL_DEFAULT')
);

$arTemplateParameters['CATALOG_MESS_NOT_AVAILABLE'] = array(
	'NAME' => Loc::getMessage('CP_BCS_TPL_MESS_NOT_AVAILABLE'),
	'TYPE' => 'STRING',
	'DEFAULT' => Loc::getMessage('CP_BCS_TPL_MESS_NOT_AVAILABLE_DEFAULT')
);
