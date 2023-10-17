<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (count($arResult['ITEMS'])) {
    $sPropCode = $arParams['PROP_FILE'];

    foreach ($arResult['ITEMS'] as &$arItem) {
        if (!isset($arItem['DISPLAY_PROPERTIES'][$sPropCode]['FILE_VALUE'])) {
            if (!empty($arItem['DETAIL_PICTURE'])) {
                $arItem['FILE_SRC'] = $arItem['DETAIL_PICTURE']['SRC'];
            } elseif (!empty($arItem['PREVIEW_PICTURE'])) {
                $arItem['FILE_SRC'] = $arItem['PREVIEW_PICTURE']['SRC'];
            }
            $arItem['HAS_FILE'] = false;
        } else {
            $arFile = $arItem['DISPLAY_PROPERTIES'][$sPropCode]['FILE_VALUE'];

            $arFileName = explode('.', $arFile['FILE_NAME']);
            $extenstion = end($arFileName);

            $arItem['HAS_FILE'] = true;
            $arItem['FILE_EXTENSION'] = strtoupper($extenstion);
            $arItem['FILE_SRC'] = $arFile['SRC'];
            $arItem['FILE_SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);
        }
    }
    unset($arItem);
}
