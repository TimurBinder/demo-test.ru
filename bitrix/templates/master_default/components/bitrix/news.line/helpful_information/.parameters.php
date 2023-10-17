<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\ParametersUtils;

if (!Loader::includeModule('redsign.master')) {
    return;
}

Loc::loadMessages(__FILE__);

if (Loader::includeModule('iblock')) {
    $rsIblocks = CIBlock::GetList(
        array(),
        array(
            'TYPE' => $arCurrentValues['IBLOCK_TYPE'],
            'ID' => $arCurrentValues['IBLOCKS'],
        ),
        false,
        array('ID', 'NAME')
    );

    while($arIblock = $rsIblocks->GetNext()) {
        $arTemplateParameters['RS_TAG_'.$arIblock['ID'].'_COLOR'] = array(
            'PARENT' => 'VISUAL',
            'TYPE' => 'SRTING',
            'NAME' => Loc::getMessage('RS.TAG_COLOR', array('#IBLOCK_NAME#' => $arIblock['NAME'])),
            'DEFAULT' => '#000'
        );
    }
}

$arTemplateParameters['RS_TITLE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.PARAMMETER_TITLE')
);


$arTemplateParameters['RS_NOT_SHOW_BTN_SUMMARY_PAGE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'CHECKBOX',
  'NAME' => Loc::getMessage('RS.PARAMETER_NOT_SHOW_BTN_SUMMARY_PAGE')
);

$arTemplateParameters['RS_BTN_TEXT_SUMMARY_PAGE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.PARAMETER_BTN_TEXT_SUMMARY_PAGE')
);

$arTemplateParameters['RS_SUMMARY_PAGE'] = array(
  'PARENT' => 'VISUAL',
  'TYPE' => 'STRING',
  'NAME' => Loc::getMessage('RS.PARAMETER_SUMMARY_PAGE')
);

ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSupport'));
if ($arCurrentValues['USE_OWL'] == 'Y') {
    $arTemplateParameters['OWL_AUTOPLAY'] = array(
        'NAME' => Loc::getMessage('RS.OWL_AUTOPLAY'),
        'TYPE' => 'CHECKBOX',
        'VALUE' => 'Y',
        'DEFAULT' => 'N'
    );
    ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('owlSettings'));
} else {
    ParametersUtils::addCommonParameters($arTemplateParameters, $arCurrentValues, array('bootstrapCols'));
}
