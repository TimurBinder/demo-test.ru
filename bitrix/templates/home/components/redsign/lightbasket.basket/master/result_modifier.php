<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


//Добавляем параметр цвет
foreach($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] as $cart)
{
	if(isset($_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$cart['ELEMENT_ID']]['COLOR']))
	{
		$arResult['ITEMS'][$cart['ELEMENT_ID']]['COLOR'] = $_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$cart['ELEMENT_ID']]['COLOR'];
	}
}

//Удаляет нулевые цены
foreach($arResult['PRICE'] as $key=>$arPrice){
	if(!$arPrice['PRICE'])unset($arResult['PRICE'][$key]);
}
