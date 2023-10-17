<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

if (isset($arResult['FIELDS'])) {
  
    foreach ($arResult['FIELDS'] as &$arField) {
        if ($arField['PROPERTY_TYPE'] != 'S') {
            continue;
        }

        $arField['INPUT_TYPE'] = 'text';

        if (isset($arFieldsParams[$arField['ID']])) {
            $arFieldParam = $arFieldsParams[$arField['ID']];

            if (!empty($arFieldParam['mask'])) {
              $arField['MASK'] = $arFieldParam['mask'];
            }

            if ($arFieldParam['validate'] == 'email') {
                $arField['INPUT_TYPE'] = 'email';
            } elseif ($arFieldParam['validate'] == 'url') {
                $arField['INPUT_TYPE'] = 'url';
            } elseif ($arFieldParam['validate'] == 'pattern' && !empty($arFieldParam['validatePattern'])) {
                $arField['PATTERN'] = $arFieldParam['validatePattern'];
            }
        }

    }
    unset($arField);

}
$arFieldsParams = CUtil::JsObjectToPhp($arParams['~FIELD_PARAMS']);


/*//Добавляем параметр цвет
foreach($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] as $cart)
{
	if(isset($_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$cart['ELEMENT_ID']]['COLOR']))
	{
		$arResult['ITEMS'][$cart['ELEMENT_ID']]['COLOR'] = $_SESSION['REDSIGN_LIGHTBASKET_COLOR'][$cart['ELEMENT_ID']]['COLOR'];
	}
}*/

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
