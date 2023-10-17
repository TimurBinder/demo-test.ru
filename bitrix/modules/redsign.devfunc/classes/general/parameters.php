<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class RSDevFuncParameters
{
    public static function GetTemplateParamsPropertiesList(int $IBLOCK_ID): ?array
    {
        if (Loader::includeModule('iblock')) {
            $arAllPropList = array();
            $arStringPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arNumberPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arSNLPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arFilePropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arListPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arHighloadPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $arEPropList = array(
                '-' => Loc::getMessage('RSDF.PROP_EMPTY')
            );
            $rsProps = CIBlockProperty::GetList(
                array('SORT' => 'ASC','ID' => 'ASC'),
                array('IBLOCK_ID' => $IBLOCK_ID,'ACTIVE' => 'Y')
            );

            while ($arProp = $rsProps->Fetch()) {
                $strPropName = '[' . $arProp['ID'] . ']' .
                    ($arProp['CODE'] != '' ? '[' . $arProp['CODE'] . ']' : '') . ' ' . $arProp['NAME'];
                $arAllPropList[$arProp['CODE']] = $strPropName;
                if ($arProp['PROPERTY_TYPE'] == 'S')
                    $arStringPropList[$arProp['CODE']] = $strPropName;
                if ($arProp['PROPERTY_TYPE'] == 'N')
                    $arNumberPropList[$arProp['CODE']] = $strPropName;
                if ($arProp['CODE'] == '')
                    $arProp['CODE'] = $arProp['ID'];
                if ($arProp['PROPERTY_TYPE'] == 'F')
                    $arFilePropList[$arProp['CODE']] = $strPropName;
                if ($arProp['PROPERTY_TYPE'] == 'L')
                    $arListPropList[$arProp['CODE']] = $strPropName;
                if (
                    $arProp['PROPERTY_TYPE'] == 'S'
                    && $arProp['USER_TYPE'] == 'directory'
                    && CIBlockPriceTools::checkPropDirectory($arProp)
                )
                    $arHighloadPropList[$arProp['CODE']] = $strPropName;

                if (
                    $arProp['PROPERTY_TYPE'] == 'S'
                    || $arProp['PROPERTY_TYPE'] == 'N'
                    || $arProp['PROPERTY_TYPE'] == 'L'
                )
                    $arSNLPropList[$arProp['CODE']] = $strPropName;
                if ($arProp['PROPERTY_TYPE'] == 'E')
                    $arEPropList[$arProp['CODE']] = $strPropName;
            }

            return array(
                'ALL' => $arAllPropList,
                'F' => $arFilePropList,
                'S' => $arStringPropList,
                'N' => $arNumberPropList,
                'L' => $arListPropList,
                'SNL' => $arSNLPropList,
                'E' => $arEPropList,
                'HL' => $arHighloadPropList,
            );
        } else {
            return null;
        }
    }

    public static function GetTemplateParamsCatalog(array $arCurrentValues = []): array
    {
        $arTemplateParams = array();

        if (Loader::includeModule('iblock') && Loader::includeModule('catalog')) {
            // IBLOCK_ID
            $arIBlocks = [];
            $res = CIBlock::GetList(array('SORT' => 'ASC'), array('ACTIVE' => 'Y'));
            while ($arIblock = $res->Fetch()) {
                $arIBlocks[$arIblock['ID']] = $arIblock['NAME'];
            }
            $arTemplateParams['IBLOCK_ID'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_IBLOCK_ID'),
                'TYPE' => 'LIST',
                'VALUES' => $arIBlocks,
                'REFRESH' => 'Y',
                'MULTIPLE' => 'Y',
            );
            // PRICES
            $arPrice = array();
            $rsPrice = CCatalogGroup::GetList(['sort' => 'asc']);
            while ($arr = $rsPrice->Fetch()) {
                $arPrice[$arr['NAME']] = '[' . $arr['NAME'] . '] ' . $arr['NAME_LANG'];
            }
            $arTemplateParams['PRICE_CODE'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_PRICE_CODE'),
                'TYPE' => 'LIST',
                'MULTIPLE' => 'Y',
                'VALUES' => $arPrice,
            );
            // VAT INCLUDE
            $arTemplateParams['PRICE_VAT_INCLUDE'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_PRICE_VAT_INCLUDE'),
                'TYPE' => 'CHECKBOX',
                'VALUE' => 'Y',
            );
            // OFFERS FIELDS
            $arTemplateParams['OFFERS_FIELD_CODE'] = CIBlockParameters::GetFieldCode(
                Loc::getMessage('RSDF.PARAM_OFFERS_FIELD_CODE'),
                'ADDITIONAL_SETTINGS'
            );

            // OFFERS PROPERTIES
            $arOffers = CIBlockPriceTools::GetOffersIBlock($arCurrentValues['IBLOCK_ID']);
            $OFFERS_IBLOCK_ID = (is_array($arOffers) ? $arOffers['OFFERS_IBLOCK_ID'] : 0 );
            $arProperty_Offers = array();
            if ($OFFERS_IBLOCK_ID > 0) {
                $rsProp = CIBlockProperty::GetList(
                    ['sort' => 'asc','name' => 'asc'],
                    [
                        'IBLOCK_ID' => $OFFERS_IBLOCK_ID,
                        'ACTIVE' => 'Y',
                    ]
                );
                while ($arr = $rsProp->Fetch()) {
                    if ($arr['PROPERTY_TYPE'] != 'F')
                        $arProperty_Offers[$arr['CODE']] = '[' . $arr['CODE'] . '] ' . $arr['NAME'];
                }
            }
            $arTemplateParams['OFFERS_PROPERTY_CODE'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_OFFERS_PROPERTY_CODE'),
                'TYPE' => 'LIST',
                'MULTIPLE' => 'Y',
                'VALUES' => $arProperty_Offers,
                'ADDITIONAL_VALUES' => 'Y',
            );
            // CONVERT CURRENCY
            $arTemplateParams['CONVERT_CURRENCY'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_CONVERT_CURRENCY'),
                'TYPE' => 'CHECKBOX',
                'DEFAULT' => 'N',
                'REFRESH' => 'Y',
            );
            // CURRENCY ID FOR CONVERT CURRENCY
            if (isset($arCurrentValues['CONVERT_CURRENCY']) && $arCurrentValues['CONVERT_CURRENCY'] == 'Y') {
                $arCurrencyList = array();
                $rsCurrencies = CCurrency::GetList(($by = 'SORT'), ($order = 'ASC'));
                while ($arCurrency = $rsCurrencies->Fetch()) {
                    $arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'];
                }
                $arTemplateParams['CURRENCY_ID'] = array(
                    'NAME' => Loc::getMessage('RSDF.PARAM_CURRENCY_ID'),
                    'TYPE' => 'LIST',
                    'VALUES' => $arCurrencyList,
                    'DEFAULT' => CCurrency::GetBaseCurrency(),
                    'ADDITIONAL_VALUES' => 'Y',
                );
            }
            // USE PRODUCT QUANTITY
            $arTemplateParams['USE_PRODUCT_QUANTITY'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_USE_PRODUCT_QUANTITY'),
                'TYPE' => 'CHECKBOX',
                'VALUE' => 'Y',
            );
            // PRODUCT QUANTITY PARAM NAME
            $arTemplateParams['PRODUCT_QUANTITY_VARIABLE'] = array(
                'NAME' => Loc::getMessage('RSDF.PARAM_PRODUCT_QUANTITY_VARIABLE'),
                'TYPE' => 'STRING',
                'VALUE' => 'quantity',
            );
        }

        return $arTemplateParams;
    }
}
