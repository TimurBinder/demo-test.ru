<?php

namespace Redsign\LightBasket;

use Bitrix\Main\Config\Option;

class Tools
{
    public static function getSelectParameters()
    {
        return array(
            'PROPERTY_'.static::getPropertyCode('price'),
            'PROPERTY_'.static::getPropertyCode('currency'),
            'PROPERTY_'.static::getPropertyCode('discount'),
        );
    }

    public static function getPropertyCode($propName)
    {
        $code = Option::get('redsign.lightbasket', 'property_code_'.$propName);

        return $code;
    }

    public static function getPriceValue($arElement)
    {
        $propertyCode = static::getPropertyCode('price');
        $price = 0;

        if (isset($arElement['PROPERTY_'.$propertyCode.'_VALUE'])) {
            $price = $arElement['PROPERTY_'.$propertyCode.'_VALUE'];

            if (is_array($price) && isset($price['TEXT'])) {
                $price = (int) $price['TEXT'];
            } else {
                $price = (int) $price;
            }
        }

        return $price;
    }

    public static function getDiscountValue($arElement)
    {
        $propertyCode = static::getPropertyCode('discount');
        $discount = 0;

        if (isset($arElement['PROPERTY_'.$propertyCode.'_VALUE'])) {
            $discount = $arElement['PROPERTY_'.$propertyCode.'_VALUE'];

            if (is_array($discount) && isset($discount['TEXT'])) {
                $discount = (int) $discount['TEXT'];
            } else {
                $discount = (int) $discount;
            }
        }

        return $discount;
    }

    public static function getCurrencyValue($arElement)
    {
        $propertyCode = static::getPropertyCode('currency');

        return isset($arElement['PROPERTY_'.$propertyCode.'_VALUE']) ? $arElement['PROPERTY_'.$propertyCode.'_VALUE'] : '';
    }

    public function findPriceByCurrency($currency, $arPrices)
    {
        foreach ($arPrices as $i => $arPrices) {
            if ($arPrices['CURRENCY'] == $currency) {
                return $i;
            }
        }

        return -1;
    }
}
