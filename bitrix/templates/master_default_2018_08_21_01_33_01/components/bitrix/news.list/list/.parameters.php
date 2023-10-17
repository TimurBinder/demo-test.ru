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

$arTemplateParameters['RS_SHOW_TITLE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'CHECKBOX',
  'DEFAULT' => 'Y',
  'NAME' => Loc::getMessage('RS.SHOW_TITLE')
);

$arTemplateParameters['RS_TITLE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.PARAMMETER_TITLE')
);

$arTemplateParameters['RS_LINK_PROP'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'LIST',
  'VALUES' => $listProp['SNL'],
  'NAME' => Loc::getMessage('RS.PARAMETER_LINK_PROP')
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));
if ($arCurrentValues['USE_OWL'] == 'Y') {
    $arTemplateParameters['OWL_AUTOPLAY'] = array(
        'NAME' => Loc::getMessage('RS.OWL_AUTOPLAY'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'N'
    );
    ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSettings'));
} else {
    ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
}
