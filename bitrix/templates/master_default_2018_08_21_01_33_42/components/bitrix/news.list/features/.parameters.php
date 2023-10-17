<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (
    !Loader::includeModule('redsign.master')
    || !Loader::includeModule('redsign.devfunc')
) {
    return false;
}

Loc::loadMessages(__FILE__);

$defaultListValues = array('-' => Loc::getMessage('RS.MASTER.UNDEFINED'));

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters = array(
    'ICON_PROP' => array(
		'NAME' => Loc::getMessage('RS.MASTER.ICON_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultListValues, $listProp['SNL']),
        'DEFAULT' => '-',
	),
    'ICON_BODYMOVIN_PROP' => array(
		'NAME' => Loc::getMessage('RS.MASTER.ICON_BODYMOVIN_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultListValues, $listProp['SNL']),
        'DEFAULT' => '-',
	),
	'LINK_PROP' => array(
		'NAME' => Loc::getMessage('RS.MASTER.LINK_PROP'),
		'TYPE' => 'LIST',
        'VALUES' => array_merge($defaultListValues, $listProp['SNL']),
        'DEFAULT' => '-',
	),
	'TARGET_PROP' => array(
		'NAME' => Loc::getMessage('RS.MASTER.TARGET_PROP'),
		'TYPE' => 'LIST',
		'VALUES' => array_merge($defaultListValues, $listProp['SNL']),
        'DEFAULT' => '-',
	),
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));

if ($arCurrentValues['USE_OWL'] == 'Y') {
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSettings'));
} else {
	ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
}
