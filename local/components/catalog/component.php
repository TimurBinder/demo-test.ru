<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

if (!CModule::IncludeModule('iblock')) {
    ShowError('Модуль «Информационные блоки» не установлен');
    return;
}

if (!isset($arParams['CACHE_TIME'])) {
    $arParams['CACHE_TIME'] = 3600;
}

$arParams['IBLOCK_TYPE'] = trim($arParams['IBLOCK_TYPE']);
$arParams['IBLOCK_ID'] = intval($arParams['IBLOCK_ID']);

$rsElements = CIBlockElement::GetList(
    ['SORT' => 'ASC'],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID']],
    false,
    false,
    ['ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTIE_*']
);

$arResult['ITEMS'] = [];

while ($obElement = $rsElements->GetNextElement()) {
    $arItem = $obElement->GetFields();
    $props = $obElement->GetProperties();
    foreach ($props as $propKey => $propValue) {
        foreach ($propValue as $key => $value) {
            if ($key == 'NAME' || $key == "VALUE")
                $arItem['PROPS'][$propKey][$key] = $value;
        }
    }

    $podezd = $arItem['PROPS']['Podezd']['VALUE'];
    if ($podezd == "" || empty($podezd))
        $podezd = "№1";
        
    $house = $arItem['PROPS']['House_number']['VALUE'];

    if ($house == "" || empty($podezd))
        $house = "Дом № 1";

    $floor = $arItem['PROPS']['Floor']['VALUE'];

    if ($floor == "" || empty($floor))
        $floor = "Этаж 1";
    $number = $arItem['PROPS']['Apartment_number']['VALUE'];
    $arResult['ITEMS'][$house][$podezd][$floor][$number] = $arItem;
}

$this->includeComponentTemplate();