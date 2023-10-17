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

$arTemplateParameters['SOCIAL_ICONS'] = array(
    'PARENT' => 'VISUAL',
    'NAME' => Loc::getMessage('RS.MI_SOCIAL_ICONS'),
    'TYPE' => 'CUSTOM',
    'JS_FILE' => ParametersUtils::getSettingsScript('socials'),
    'JS_EVENT' => 'CustomSettingsEdit',
    'JS_DATA' => str_replace('\'',"\"", CUtil::PhpToJSObject(
  			array(
    				'labelPage' => GetMessage('RS.MI_SOCIAL_ICONS_PAGE'),
    				'labelIcon' => GetMessage('RS.MI_SOCIAL_ICONS_ICON'),
  			)
    )),
	'DEFAULT' => "",
);
