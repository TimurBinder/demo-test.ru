<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('redsign.master')) {
    return;
}

Loc::loadMessages(__FILE__);

$arTemplateParameters['RS_TITLE'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_ABOUT_COMPANY_TITLE'),
    'TYPE' => 'STRING'
);

$arTemplateParameters['COMPANY_ACHIVEMENTS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_ABOUT_COMPANY_ACHIVEMENTS'),
    'TYPE' => 'CUSTOM',
    'JS_FILE' => ParametersUtils::getSettingsScript('about_company'),
    'JS_EVENT' => 'CustomSettingsEdit',
    'JS_DATA' => str_replace('\'',"\"", CUtil::PhpToJSObject(
  			array(
    				'labelNumber' => GetMessage('RS.MI_PARAMETERS_ABOUT_COMPANY_ACHIVEMENTS_NUMBERS'),
    				'labelContent' => GetMessage('RS.MI_PARAMETERS_ABOUT_COMPANY_ACHIVEMENTS_DESC'),
  			)
    )),
	'DEFAULT' => "",
);
