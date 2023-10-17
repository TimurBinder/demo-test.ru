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

$arTemplateParameters['BLOCK_TYPE'] = array(
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_TYPE'),
    'PARENT' => 'VISUAL',
    'TYPE' => 'LIST',
    'VALUES' => array(
      'wide' => Loc::getMessage('RS.MI_PARAMETERS_TYPE_WIDE'),
      'narrow' => Loc::getMessage('RS.MI_PARAMETERS_TYPE_NARROW')
    ),
    'DEFAULT' => 'wide'
);

$arTemplateParameters['BTN_LINK'] = array(
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_BTN_LINK'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS.MI_PARAMETERS_BTN_LINK_DEFAULT'),
);
$arTemplateParameters['BTN_TEXT'] = array(
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_BTN_TEXT'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS.MI_PARAMETERS_BTN_TEXT_DEFAULT'),
);
