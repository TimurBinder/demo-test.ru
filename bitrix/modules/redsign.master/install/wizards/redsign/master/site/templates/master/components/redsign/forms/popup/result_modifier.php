<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!isset($arParams['USE_B24_CRM_FORM'])) {
    $arParams['USE_B24_CRM_FORM'] = 'N';
}

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

        if (!empty($arFieldParam['placeholder'])) {
          $arField['PLACEHOLDER'] = $arFieldParam['placeholder'];
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
