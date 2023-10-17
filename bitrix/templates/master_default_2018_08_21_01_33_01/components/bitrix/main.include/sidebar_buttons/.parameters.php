<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
  die();
}
use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplateParameters = array(
    'LINK_SIDE_WIDGET' => array(
        'NAME' => Loc::getMessage('RS.LINK_SIDE_WIDGET'),
        'TYPE' => 'STRING',
        'DEFAULT' => '/include/forms/recall/',
    ),
    'NAME_SIDE_SVG' => array(
        'NAME' => Loc::getMessage('RS.NAME_SIDE_SVG'),
        'TYPE' => 'STRING',
        'DEFAULT' => 'location',
    ),
    'IS_LINK_POPUP' => array(
        'NAME' => Loc::getMessage('RS.IS_LINK_POPUP'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'Y',
    ),
);