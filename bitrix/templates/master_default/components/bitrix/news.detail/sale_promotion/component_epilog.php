<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

if (count($arResult['RELATE_PROPS']) > 0) {
    ob_start();
    foreach ($arResult['RELATE_PROPS'] as $arProp) {            
        $sIncludeAreaPath = $arParams['DETAIL_PATH_TO_'.$arProp['CODE'].'_AREA'];
        $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'PATH' => $sIncludeAreaPath,
                'IBLOCK_ID' => $arProp['LINK_IBLOCK_ID'],
                'BLOCK_NAME' => $arProp['NAME'],
                'FILTER' => $arProp['VALUE']
            )
        );
    }
    $APPLICATION->AddViewContent('nd_link_items', ob_get_clean());
}