<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('redsign.master') || !Loader::includeModule('redsign.devfunc')) {
    return;
}

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters['RS_TITLE'] = array(
    'NAME' => Loc::getMessage('RS.TITLE'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'STRING'
);

$arTemplateParameters['PROP_FILE'] = array(
    'NAME' => Loc::getMessage('RS.PARAMETERS_PROP_FILE'),
		'TYPE' => 'LIST',
		'VALUES' => $listProp['F'],
);

$arTemplateParameters['USE_GALLERY'] = array(
    'NAME' => Loc::getMessage('RS.USE_GALLERY'),
    'TYPE' => 'CHECKBOX',
    'DEFAULT' => 'N'
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
