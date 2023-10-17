<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);


$arTemplateParameters['BLOCK_TITLE'] = array(
    'NAME' => Loc::getMessage('RS.BLOCK_TITLE'),
    'TYPE' => 'STRING',
    'DEFAULT' => ''
);
