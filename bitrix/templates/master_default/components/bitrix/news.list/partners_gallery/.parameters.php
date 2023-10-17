<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

foreach (array('iblock', 'redsign.master', 'redsign.devfunc') as $module) {
    if (!Loader::includeModule($module)) {
        return;
    }
}

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('blockName', 'owlSupport'));

$arTemplateParameters['RS_PROP_MORE_PHOTO'] = array(
    'PARENT' => 'DETAIL',
    'NAME' => Loc::getMessage('RS.MORE_PHOTO'),
    'TYPE' => 'LIST',
    'VALUES' => $listProp['F'],
);

$arTemplateParameters['RS_NOT_SHOW_BTN_SUMMARY_PAGE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'CHECKBOX',
  'NAME' => Loc::getMessage('RS.PARAMETER_NOT_SHOW_BTN_SUMMARY_PAGE')
);

$arTemplateParameters['RS_BTN_TEXT_SUMMARY_PAGE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.PARAMETER_BTN_TEXT_SUMMARY_PAGE')
);
