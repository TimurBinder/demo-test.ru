<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (count($arResult['SEARCH']) > 0) {
    $arIblocks = array();
    $arIblocksIds = array();
    $arIblocksItems = array();
    $arOtherItems = array();

    foreach ($arResult['SEARCH'] as $arItem) {
        if ($arItem['MODULE_ID'] == 'iblock') {
            if (!in_array($arItem['PARAM2'], $arIblocksIds)) {
                $arIblocksIds[] = $arItem['PARAM2'];
            }
        } else {
            $arOtherItems[] = $arItem;
        }
    }
     
    foreach ($arIblocksIds as $arIblockId) {
    	$arIblockSearchItems = array();
    	foreach ($arResult['SEARCH'] as $arSearchItem) {
    		$arIblockSearchItems[] = $arSearchItem;	
		}
    	$arIblocksItems[$arIblockId] = $arIblockSearchItems;
	}

    if (count($arIblocksIds) > 0) {
        $iblockIterator = \Bitrix\Iblock\IblockTable::getList(array(
            'filter' => array(
                '=ID' => $arIblocksIds
            )
        ));

        while ($arIblock = $iblockIterator->fetch()) {
            $arIblock['ITEMS'] = $arIblocksItems[$arIblock['ID']];
            $arIblocks[] = $arIblock;
        }
    }

    $arResult['SEARCH_EXT'] = array(
        'IBLOCKS' => $arIblocks,
        'OTHER' => array(
            'ITEMS' => $arOtherItems
        )
    );
    unset($arIblocks);
    unset($arIblockIds);
    unset($arIblocksItems);
    unset($arOtherItems);
}
