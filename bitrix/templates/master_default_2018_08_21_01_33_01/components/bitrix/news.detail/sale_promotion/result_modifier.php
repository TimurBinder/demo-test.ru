<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

if (!Loader::includeModule('redsign.devfunc')) {
    return;
}

$arResult['RELATE_PROPS'] = array();
if (count($arResult['DISPLAY_PROPERTIES'] > 0) && Loader::includeModule('iblock')) {
    $uploadDir = \Bitrix\Main\Config\Option::get('main', 'upload_dir', 'upload');
    foreach ($arResult['DISPLAY_PROPERTIES'] as &$arProp) {
        if ($arProp['PROPERTY_TYPE'] == 'F' && count($arProp['VALUE']) > 0) {
            if ($arProp['CODE'] == $arParams['ADD_PICT_PROP']) {
                continue;
            }
            $filesIterator = \Bitrix\Main\FileTable::getList(array(
                'filter' => array(
                    'ID' => $arProp['VALUE'],
                ),
            ));

            $arProp['DISPLAY_VALUE'] = $filesIterator->fetchAll();

            if ($arProp['DISPLAY_VALUE'] > 0) {
                foreach ($arProp['DISPLAY_VALUE'] as &$arFile) {
                    $arFile['SRC'] = '/'.$uploadDir.'/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
                }
                unset($arFile);
            }
        }

        if ($arProp['PROPERTY_TYPE'] == 'E' && count($arProp['VALUE']) > 0) {
            $arResult['RELATE_PROPS'][] = $arProp;
        }
    }
    unset($arProp);

    $component = $this->GetComponent();
    $component->SetResultCacheKeys(array('RELATE_PROPS'));
}
