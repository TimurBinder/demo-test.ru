<?php

namespace Redsign\DevFunc\Iblock;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Property
{
    public static function OnIBlockPropertyBuildListStores(): array
    {
        return [
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'redsign_stores',
            'DESCRIPTION' => Loc::getMessage('REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_STORES_TITLE'),
            'GetPropertyFieldHtml' => array(
                '\Redsign\DevFunc\Iblock\Property',
                'GetPropertyFieldHtmlStores'
            ),
            'GetPropertyFieldHtmlMulty' => array(
                '\Redsign\DevFunc\Iblock\Property',
                'GetPropertyFieldHtmlStoresMulty'
            ),
        ];
    }

    public static function GetPropertyFieldHtmlStores(
        array $arProperty,
        array $value,
        array $strHTMLControlName
    ): string {
        static $cache = [];
        $html = '';

        if (Loader::includeModule('catalog')) {
            $cache['STORES'] = [];
            $rsStore = \CCatalogStore::GetList(['SORT' => 'ASC'], []);

            while ($arStore = $rsStore->GetNext()) {
                $cache['STORES'][] = $arStore;
            }

            $varName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
            $val = ($value['VALUE'] ? $value['VALUE'] : $arProperty['DEFAULT_VALUE']);
            if ($arProperty['MULTIPLE'] == 'Y') {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '[]" ' .
                    'multiple size="6" ' .
                    'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                    '.value=this.options[this.selectedIndex].text">';
            } else {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '" ' .
                    'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                    '.value=this.options[this.selectedIndex].text">';
            }

            $html .= '<option value="component" ' . ($val == "component" ? 'selected' : '') . '>' .
                Loc::getMessage("REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_FROM_COMPONENTS") . '</option>';

            foreach ($cache['STORES'] as $arStore) {
                $html .= '<option value="' . $arStore['ID'] . '"';
                if ($val == $arStore['~ID'])
                    $html .= ' selected';
                $html .= '>' . $arStore['TITLE'] . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }

    public static function GetPropertyFieldHtmlStoresMulty(
        array $arProperty,
        array $value,
        array $strHTMLControlName
    ): string {
        static $cache = [];
        $html = '';

        if (Loader::includeModule('catalog')) {
            $cache['STORES'] = [];
            $rsStore = \CCatalogStore::GetList(['SORT' => 'ASC'], []);

            while ($arStore = $rsStore->GetNext()) {
                $cache['STORES'][] = $arStore;
            }

            $varName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
            $arValues = [];

            if ($value && is_array($value)) {
                foreach ($value as $arValue) {
                    $arValues[] = $arValue['VALUE'];
                }
            } else {
                $arValues[] = $arProperty['DEFAULT_VALUE'];
            }

            if ($arProperty['MULTIPLE'] == 'Y') {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '[]" ' .
                'multiple size="6" ' .
                'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                '.value=this.options[this.selectedIndex].text">';
            } else {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '" ' .
                    'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                    '.value=this.options[this.selectedIndex].text">';
            }

            $html .= '<option value="component" ' . (in_array("component", $arValues) ? 'selected' : '') . '>' .
                Loc::getMessage("REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_FROM_COMPONENTS") . '</option>';

            foreach ($cache['STORES'] as $arStore) {
                $html .= '<option value="' . $arStore['ID'] . '"';
                if (in_array($arStore['~ID'], $arValues)) {
                    $html .= ' selected';
                }
                $html .= '>' . $arStore['TITLE'] . '</option>';
            }
            $html .= '</select>';
        }

        return $html;
    }

    public static function OnIBlockPropertyBuildListLocations(): array
    {
        return [
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'redsign_locations',
            'DESCRIPTION' => Loc::getMessage('REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_LOCATIONS_TITLE'),
            'GetPropertyFieldHtml' => array(
                '\Redsign\DevFunc\Iblock\Property',
                'GetPropertyFieldHtmlLocations'
            ),
        ];
    }

    public static function GetPropertyFieldHtmlLocations(
        array $arProperty,
        array $value,
        array $strHTMLControlName
    ): string {
        static $cache = [];
        $html = '';

        if (Loader::includeModule('sale')) {
            $cache['LOCATIONS'] = [];
            $rsLoc = \CSaleLocation::GetList(['CITY_NAME' => 'ASC'], []);

            while ($arLoc = $rsLoc->GetNext()) {
                if ($arLoc['CITY_NAME']) {
                    $cache['LOCATIONS'][$arLoc['ID']] = $arLoc;
                }
            }

            $varName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
            $val = ($value['VALUE'] ? $value['VALUE'] : $arProperty['DEFAULT_VALUE']);
            $html = '<select name="' . $strHTMLControlName['VALUE'] . '" ' .
                'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                '.value=this.options[this.selectedIndex].text">
            <option value="" >-</option>';
            foreach ($cache['LOCATIONS'] as $arLocation) {
                $html .= '<option value="' . $arLocation['ID'] . '"';
                if ($val == $arLocation['~ID']) {
                    $html .= ' selected';
                }
                $html .= '>' . $arLocation['CITY_NAME'] . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }

    public static function OnIBlockPropertyBuildListPrices(): array
    {
        return [
            'PROPERTY_TYPE' => 'S',
            'USER_TYPE' => 'redsign_prices',
            'DESCRIPTION' => Loc::getMessage('REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_PRICES_TITLE'),
            'GetPropertyFieldHtml' => array(
                '\Redsign\DevFunc\Iblock\Property',
                'GetPropertyFieldHtmlPrices'
            ),
            'GetPropertyFieldHtmlMulty' => array(
                '\Redsign\DevFunc\Iblock\Property',
                'GetPropertyFieldHtmlPricesMulty'
            ),
        ];
    }


    public static function GetPropertyFieldHtmlPrices(
        array $arProperty,
        array $value,
        array $strHTMLControlName
    ): string {
        static $cache = [];
        $html = '';

        if (Loader::includeModule('catalog')) {
            $cache['PRICE'] = [];
            $rsPrice = \CCatalogGroup::GetList(['SORT' => 'ASC'], []);
            while ($arPrice = $rsPrice->GetNext()) {
                $cache['PRICE'][] = $arPrice;
            }

            $varName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
            $val = ($value['VALUE'] ? $value['VALUE'] : $arProperty['DEFAULT_VALUE']);
            $html = '<select name="' . $strHTMLControlName['VALUE'] . '" ' .
                'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                '.value=this.options[this.selectedIndex].text">' .
                    '<option value="component" ' . ($val == "component" ? 'selected' : '') . '>' .
                        Loc::getMessage('REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_FROM_COMPONENTS') .
                    '</option>';

            foreach ($cache['PRICE'] as $arPrice) {
                $html .= '<option value="' . $arPrice['ID'] . '"';
                if ($val == $arPrice['~ID']) {
                    $html .= ' selected';
                }
                $html .= '>' . $arPrice['NAME'] . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }

    public static function GetPropertyFieldHtmlPricesMulty(
        array $arProperty,
        array $value,
        array $strHTMLControlName
    ): string {
        static $cache = [];
        $html = '';
        if (Loader::includeModule('catalog')) {
            $cache['PRICE'] = [];
            $rsPrice = \CCatalogGroup::GetList(['SORT' => 'ASC'], []);
            while ($arPrice = $rsPrice->GetNext()) {
                $cache['PRICE'][] = $arPrice;
            }

            $varName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
            $arValues = [];
            if ($value && is_array($value)) {
                foreach ($value as $arValue) {
                    $arValues[] = $arValue['VALUE'];
                }
            } else {
                $arValues[] = $arProperty['DEFAULT_VALUE'];
            }

            if ($arProperty['MULTIPLE'] == 'Y') {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '[]" ' .
                    'multiple size="6"' .
                    'onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                    '.value=this.options[this.selectedIndex].text">';
            } else {
                $html .= '<select name="' . $strHTMLControlName['VALUE'] . '"' .
                    ' onchange="document.getElementById(\'DESCR_' . $varName . '\')' .
                    '.value=this.options[this.selectedIndex].text">';
            }

            $html .= '<option value="component" ' . (in_array('component', $arValues) ? 'selected' : '') . '>'
                . Loc::getMessage('REDSIGN_DEVFUNC_IBLOCK_PROPERTY_LINK_PROP_FROM_COMPONENTS') . '</option>';
            foreach ($cache['PRICE'] as $arPrice) {
                $html .= '<option value="' . $arPrice['ID'] . '"';
                if (in_array($arPrice['~ID'], $arValues)) {
                    $html .= ' selected';
                }
                $html .= '>' . $arPrice['NAME'] . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }
}
