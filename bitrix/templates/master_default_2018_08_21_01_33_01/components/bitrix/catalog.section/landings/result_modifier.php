<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;

$arResult["PRICES"] = array();

if (intval($arParams['LANDING_ELEMENT_ID']) > 0) {
  
  $selectElem = array();
  $propForGroup = array();
  $valPrice = "";
  $picts = "";
  $selectElem[] = "ID";
  $selectElem[] = "IBLOCK_ID";
  $selectElem[] = "NAME";
  $selectElem[] = "DETAIL_PAGE_URL";
  $selectElem[] = "SECTION_ID";

  $filterElem = Array("IBLOCK_ID"=>$arParams["LANDING_ELEMENT_IBLOCK_ID"], "ID" => $arParams['LANDING_ELEMENT_ID']);
  $res = CIBlockElement::GetList(Array(), $filterElem, false, Array("nPageSize"=>1), $selectElem);
  while($ob = $res->GetNextElement()){
    $arResult["ELEMENT"] = $ob->GetFields();
    $arResult["ELEMENT"]["PROPERTIES"] = $ob->GetProperties();
  }

  if (is_array($arResult["ELEMENT"]["PROPERTIES"])) {
    foreach ($arResult["ELEMENT"]["PROPERTIES"] as $prop) {
      if ($prop["CODE"] == $arParams['PRICE_PROP'])
        $valPrice = $prop["VALUE"];
      elseif ($prop["CODE"] == $arParams['DISCOUNT_PROP'])
        $valDiscount = $prop["VALUE"];
      elseif ($prop["CODE"] == $arParams['CURRENCY_PROP'])
        $valCurrency = $prop["VALUE"];
      elseif ($prop["CODE"] == $arParams['ADD_PICT_PROP'])
        $picts = $prop;
      elseif (in_array($prop["CODE"], $arParams["PROPERTY_CODE_ELEMENT"]) && $prop["VALUE"] != "")
        $propForGroup[] = $prop;
    }
  }

  $arResult["ELEMENT"]["PROP_GROUP"] = $propForGroup;

  if (!empty($picts)) {
    foreach ($picts["VALUE"] as $propPic) {
      $picts["IMAGES"][]["SRC"] = CFile::GetPath($propPic);
    }
  }
  $arResult["ELEMENT"]["PICTURES"] = $picts;
}

if (Loader::includeModule('redsign.master') && $valPrice != "") {

    $params = array(
      'PROP_PRICE' => $arParams['PRICE_PROP'],
      'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
      'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
      'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
    );

    $itemPrice[$arParams['PRICE_PROP']]["VALUE"] = $valPrice;
    $itemPrice[$arParams['DISCOUNT_PROP']]["VALUE"] = $valDiscount;
    $itemPrice[$arParams['CURRENCY_PROP']]["VALUE"] = $valCurrency;

    $arResult["ELEMENT"]["PRICES"] = IblockElementExt::formatPrices($itemPrice, $params);
}


$arParams['OWL_PARAMS'] = array(
  'items' => 1,
  'margin' => 0,
  'dots' => false,
  'nav' => false,
  'responsive' => array(
      '0' => array('items' => '1'),
      '480' => array('items' => '2'),
      '769' => array('items' => '3'),
      '960' => array('items' => '4'),
      '1200' => array('items' => '5')
  ),
);

