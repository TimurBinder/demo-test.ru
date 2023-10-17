<?php

namespace Redsign\LightBasket;

use Bitrix\Main\Config\Option;

class Tools
{
    public static function getSelectParameters(): array
    {
        return [
            'PROPERTY_' . static::getPropertyCode('price'),
            'PROPERTY_' . static::getPropertyCode('currency'),
            'PROPERTY_' . static::getPropertyCode('discount'),
        ];
    }

    public static function getPropertyCode(string $propName): ?string
    {
        $code = Option::get('redsign.lightbasket', 'property_code_' . $propName);

        return $code;
    }

    public static function getPriceValue(array $arElement): float
    {
        $propertyCode = static::getPropertyCode('price');
        $price = 0;

        if (isset($arElement['PROPERTY_' . $propertyCode . '_VALUE'])) {
            $price = $arElement['PROPERTY_' . $propertyCode . '_VALUE'];

            if (is_array($price) && isset($price['TEXT'])) {
                $price = (float) $price['TEXT'];
            } else {
                $price = (float) $price;
            }
        }

        return $price;
    }

    public static function getDiscountValue(array $arElement): float
    {
        $propertyCode = static::getPropertyCode('discount');
        $discount = 0;

        if (isset($arElement['PROPERTY_' . $propertyCode . '_VALUE'])) {
            $discount = $arElement['PROPERTY_' . $propertyCode . '_VALUE'];

            if (is_array($discount) && isset($discount['TEXT'])) {
                $discount = (float) $discount['TEXT'];
            } else {
                $discount = (float) $discount;
            }
        }

        return $discount;
    }

    public static function getCurrencyValue(array $arElement): string
    {
        $propertyCode = static::getPropertyCode('currency');

        return isset($arElement['PROPERTY_' . $propertyCode . '_VALUE'])
            ? $arElement['PROPERTY_' . $propertyCode . '_VALUE']
            : '';
    }

    /**
     * @param string $currency
     * @param array $arPrices
     *
     * @return int|string
     */
    public static function findPriceByCurrency(string $currency, array $arPrices)
    {
        foreach ($arPrices as $i => $arPrices) {
            if ($arPrices['CURRENCY'] == $currency) {
                return $i;
            }
        }

        return -1;
    }
}
