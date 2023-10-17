<?php
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arTemplate = Array(
	'NAME' => Loc::getMessage('REDSIGN.MASTER.HOMEPAGE.TYPE4'),
	'SORT' => 400,
	'DEFAULT' => 'Y',
);
