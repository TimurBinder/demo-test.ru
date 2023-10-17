<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateParameters['BLOCK_TITLE'] = array(
    'NAME' => Loc::getMessage('RS.BLOCK_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => Loc::getMessage('RS.BLOCK_TITLE_DEFAULT')
);

$arTemplateParameters['RS_VK_GROUP_ID'] = array(
    'NAME' => Loc::getMessage('RS.VK_GROUP_ID'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);

$arTemplateParameters['RS_FB_PAGE'] = array(
    'NAME' => Loc::getMessage('RS.FB_PAGE'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);
