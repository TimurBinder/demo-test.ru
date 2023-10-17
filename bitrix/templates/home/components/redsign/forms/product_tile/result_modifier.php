<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

$arFieldsParams = CUtil::JsObjectToPhp($arParams['~FIELD_PARAMS']);

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


if (Loader::includeModule('iblock')) {
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    $itemId = $request->getQuery('element_id');

    $res = CIBlockElement::GetList(
        array(),
        array(
            'ID' => $itemId,
            'IBLOCK_ID' => $arParams['ITEMS_IBLOCK_ID'],
        ),
        array(
            'ID',
            'NAME'
        )
    );

    $arResult['ELEMENT'] = array();
    while ($arElement = $res->GetNext()) {
        if (empty($arResult['ELEMENT']['ID'])) {
            $arResult['ELEMENT']['ID'] = $arElement['ID'];
        }
        if (empty($arResult['ELEMENT']['NAME'])) {
            $arResult['ELEMENT']['NAME'] = $arElement['NAME'];
        }
    }

    foreach ($arResult['FIELDS'] as &$arField) {
        if ($arField['CODE'] == $arParams['NAME_PROPERTY_CODE']) {
            $arField['CURRENT_VALUE'] = '['.$arResult['ELEMENT']['ID'].'] '.$arResult['ELEMENT']['NAME'];
        }
    }
    unset($arField);
}


// получаем возможные варианты цветов и цен для тротуара, кевлара и т.д. (аналог SKU)
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$element_id = ($_GET['element_id']) ? (int)$_GET['element_id'] : '';
if ($element_id) {

	$hl_codes = array();
    $res = CIBlockElement::GetProperty(3, $element_id, array("sort" => "asc"), array("CODE" => "COLOR_REF"));
    while ($ob = $res->GetNext())
    {
        $hl_codes[] = $ob['VALUE'];
    }
	
	CModule::IncludeModule('iblock');
	CModule::IncludeModule('highloadblock');
	
	$hlblock = HL\HighloadBlockTable::getById(1)->fetch();
	$entity = HL\HighloadBlockTable::compileEntity($hlblock);
	$entityClass = $entity->getDataClass();
	
	$colors = array();
	foreach ($hl_codes as $hl_code) {
	   $res = $entityClass::getList(array(
	       'select' => array('*'),
	       'order' => array('ID' => 'ASC'),
	       'filter' => array('UF_XML_ID' => $hl_code)
	   ));
	   $color = array();	   
	   $color = $res->fetch();
	   $color['UF_FILE_PATH'] = CFile::GetPath($color["UF_FILE"]);	   	   	   
	   $colors[] = $color;
	}
	
	$color_prices = array();
    $res = CIBlockElement::GetProperty(3, $element_id, array("sort" => "asc"), array("CODE" => "PRICE_COLOR"));
    while ($ob = $res->GetNext())
    {
        $color_prices[] = $ob['VALUE'];
    }
	
	if (is_array($colors) && is_array($color_prices) && count($colors) > 0 && count($color_prices) > 0 && count($colors) == count($color_prices)) {
		$arResult['EXT']['COLORS'] = $colors;
		$arResult['EXT']['COLOR_PRICES'] = $color_prices;
	}
}




