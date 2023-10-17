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

$arTemplateParameters['MARKER_TEXT_PROPERTY'] = array(
    'NAME' => Loc::getMessage('RS.MARKER_TEXT_PROPERTY'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL']
);
$arTemplateParameters['MARKER_COLOR_PROPERTY'] = array(
    'NAME' => Loc::getMessage('RS.MARKER_COLOR_PROPERTY'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL']
);
$arTemplateParameters['SALE_DATE_PROPERTY'] = array(
    'NAME' => Loc::getMessage('RS.SALE_DATE_PROPERTY'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['SNL']
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
