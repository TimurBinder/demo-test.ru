<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('redsign.master')) {
    return;
}

Loc::loadMessages(__FILE__);

$arTemplateParameters['RS_LINK'] = array(
    'NAME' => Loc::getMessage('RS.MI_PARAMETERS_SIDEBAR_MAP_LINK'),
    'TYPE' => 'STRING'
);
