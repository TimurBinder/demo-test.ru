<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
  die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('iblock'))
	return;
if (!Loader::includeModule('redsign.master'))
	return;
if (!Loader::includeModule('redsign.devfunc'))
	return;

Loc::loadMessages(__FILE__);

$listProp = RSDevFuncParameters::GetTemplateParamsPropertiesList($arCurrentValues['IBLOCK_ID']);

$arTemplateParameters = array(
  	'RS_AUTHOR_NAME' => array(
    		'NAME' => Loc::getMessage('RS.REVIEWS_AUTHOR_NAME'),
    		'TYPE' => 'LIST',
    		'VALUES' => $listProp['SNL'],
  	),
  	'RS_AUTHOR_JOB' => array(
    		'NAME' => Loc::getMessage('RS.REVIEWS_AUTHOR_JOB'),
    		'TYPE' => 'LIST',
    		'VALUES' => $listProp['SNL'],
  	),
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('blockName','owlSupport'));

if ($arCurrentValues['USE_OWL'] != 'Y') {
    $arTemplateParameters['IS_AJAX_PAGER'] = array(
        'PARENT' => 'PAGER_SETTINGS',
        'NAME' => Loc::getMessage('RS.REVIEWS_SHOW_MORE_BTN'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    );

    $arTemplateParameters['SHOW_DESCRIPTION'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.SHOW_DESCRIPTION'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y'
    );

    $arTemplateParameters['SHOW_FEEDBACK_BUTTON'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.SHOW_FEEDBACK_BUTTON'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
        'REFRESH' => 'Y'
    );
}

if ($arCurrentValues['SHOW_FEEDBACK_BUTTON'] == 'Y') {
    $arTemplateParameters['FEEDBACK_BUTTON_LINK'] = array(
        'PARENT' => 'VISUAL',
        'NAME' => Loc::getMessage('RS.FEEDBACK_BUTTON_LINK'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/forms/feedback/'
    );
}
