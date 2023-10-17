<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

if (!Loader::includeModule('redsign.devfunc')) {
    return;
}

$arResult['RIGHT_WORD'] = RSDevFunc::BasketEndWord(count($arResult['ITEMS']));

//Добавляем параметр цвет
foreach($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] as $k => $v)
{
	if(isset($_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$k]))
	{
		$arResult['ITEMS'][$k]['COLOR'] = $_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$k];
	}
}
//Добавляем параметр вид
foreach($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] as $k => $v)
{
	if(isset($_SESSION['REDSIGN_LIGHTBASKET_KIND'][$k]))
	{
		$arResult['ITEMS'][$k]['KIND'] = $_SESSION['REDSIGN_LIGHTBASKET_KIND'][$k];
	}
}

//Удаляет нулевые цены
foreach($arResult['PRICE'] as $key=>$arPrice){
	if(!$arPrice['PRICE'])unset($arResult['PRICE'][$key]);
}
