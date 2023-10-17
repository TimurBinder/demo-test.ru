<?php

namespace Redsign\DevFunc\Catalog;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main;
use Bitrix\Catalog;

Loc::loadMessages(__FILE__);

require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/catalog/general/product.php");

class Product extends \CAllCatalogProduct
{
    /**
     * @param int $intProductID
     * @param int|float $quantity
     * @param array|int $arUserGroups
     * @param string $renewal
     * @param ?array $priceList
     * @param bool|string $siteID
     * @param bool|array $arDiscountCoupons
     * @param array $arRegion
     * @return array|bool
     */
    public static function GetOptimalPrice(
        $intProductID,
        $quantity = 1,
        $arUserGroups = [],
        $renewal = 'N',
        $priceList = [],
        $siteID = false,
        $arDiscountCoupons = false,
        $arRegion = []
    ) {
        /** @var \CMain $APPLICATION */
        global $APPLICATION;

        $intProductID = (int)$intProductID;
        if ($intProductID <= 0) {
            $APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_PRODUCT_ID_ABSENT"), "NO_PRODUCT_ID");
            return false;
        }

        $quantity = (float)$quantity;
        if ($quantity <= 0) {
            $APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_QUANTITY_ABSENT"), "NO_QUANTITY");
            return false;
        }

        if (!is_array($arUserGroups) && (int)$arUserGroups.'|' == (string)$arUserGroups.'|')
            $arUserGroups = array((int)$arUserGroups);

        if (!is_array($arUserGroups))
            $arUserGroups = array();

        if (!in_array(2, $arUserGroups))
            $arUserGroups[] = 2;
        Main\Type\Collection::normalizeArrayValuesByInt($arUserGroups);

        $renewal = ($renewal == 'Y' ? 'Y' : 'N');

        if ($siteID === false)
            $siteID = SITE_ID;

        $resultCurrency = Catalog\Product\Price\Calculation::getCurrency();
        if (empty($resultCurrency)) {
            $APPLICATION->ThrowException(Loc::getMessage("BT_MOD_CATALOG_PROD_ERR_NO_RESULT_CURRENCY"));
            return false;
        }

        $intIBlockID = (int)\CIBlockElement::GetIBlockByID($intProductID);
        if ($intIBlockID <= 0) {
            $APPLICATION->ThrowException(
                Loc::getMessage(
                    'BT_MOD_CATALOG_PROD_ERR_ELEMENT_ID_NOT_FOUND',
                    array('#ID#' => $intProductID)
                ),
                'NO_ELEMENT'
            );
            return false;
        }

        if (!isset($priceList) || !is_array($priceList))
            $priceList = array();

        if (empty($priceList)) {
            $priceTypeList = self::getAllowedPriceTypes($arUserGroups);

            if (is_array($arRegion['LIST_PRICES']) && reset($arRegion['LIST_PRICES']) !== 'component') {
                $priceTypeList = array_filter(
                    $priceTypeList,
                    function ($v) use ($arRegion) {
                        return in_array($v, array_column($arRegion['LIST_PRICES'], 'ID'));
                    }
                );
            }

            if (empty($priceTypeList))
                return false;

            $iterator = Catalog\PriceTable::getList(array(
                'select' => array('ID', 'CATALOG_GROUP_ID', 'PRICE', 'CURRENCY', 'PRICE_SCALE'),
                'filter' => array(
                    '=PRODUCT_ID' => $intProductID,
                    '@CATALOG_GROUP_ID' => $priceTypeList,
                    array(
                        'LOGIC' => 'OR',
                        '<=QUANTITY_FROM' => $quantity,
                        '=QUANTITY_FROM' => null
                    ),
                    array(
                        'LOGIC' => 'OR',
                        '>=QUANTITY_TO' => $quantity,
                        '=QUANTITY_TO' => null
                    )
                ),
                'order' => array('CATALOG_GROUP_ID' => 'ASC')
            ));
            while ($row = $iterator->fetch()) {
                $row['ELEMENT_IBLOCK_ID'] = $intIBlockID;
                $priceList[] = $row;
            }
            unset($row, $iterator);
            unset($priceTypeList);
        } else {
            foreach (array_keys($priceList) as $priceIndex)
                $priceList[$priceIndex]['ELEMENT_IBLOCK_ID'] = $intIBlockID;
            unset($priceIndex);
        }

        if (empty($priceList))
            return false;

        $vat = \CCatalogProduct::GetVATDataByID($intProductID);
        if (!empty($vat)) {
            if ($vat['EXCLUDE_VAT'] === 'N') {
                $vat['RATE'] = $vat['RATE'] * 0.01;
            }
        } else {
            $vat = [
                'RATE' => null,
                'VAT_INCLUDED' => 'N',
                'EXCLUDE_VAT' => 'Y',
            ];
        }
        unset($iterator);

        $isNeedDiscounts = Catalog\Product\Price\Calculation::isAllowedUseDiscounts();
        $resultWithVat = Catalog\Product\Price\Calculation::isIncludingVat();
        if ($isNeedDiscounts) {
            if ($arDiscountCoupons === false)
                $arDiscountCoupons = \CCatalogDiscountCoupon::GetCoupons();
        }

        $minimalPrice = array();

        // if (self::$saleIncluded === null)
            // self::initSaleSettings();
        // $isNeedleToMinimizeCatalogGroup = self::isNeedleToMinimizeCatalogGroup($priceList);

        foreach ($priceList as $priceData) {
            $priceData['VAT_RATE'] = $vat['RATE'];
            $priceData['VAT_INCLUDED'] = $vat['VAT_INCLUDED'];
            $priceData['NO_VAT'] = $vat['EXCLUDE_VAT'];

            $currentPrice = (float)$priceData['PRICE'];
            if ($priceData['NO_VAT'] === 'N') {
                if ($priceData['VAT_INCLUDED'] === 'N') {
                    $currentPrice *= (1 + $priceData['VAT_RATE']);
                }
            }
            if ($priceData['CURRENCY'] != $resultCurrency) {
                $currentPrice = \CCurrencyRates::ConvertCurrency(
                    $currentPrice,
                    $priceData['CURRENCY'],
                    $resultCurrency
                );
            }

            $currentPrice = Catalog\Product\Price\Calculation::roundPrecision($currentPrice);

            $result = array(
                'BASE_PRICE' => $currentPrice,
                'COMPARE_PRICE' => $currentPrice,
                'PRICE' => $currentPrice,
                'CURRENCY' => $resultCurrency,
                'DISCOUNT_LIST' => array(),
                'RAW_PRICE' => $priceData
            );
            if ($isNeedDiscounts) {
                $arDiscounts = \CCatalogDiscount::GetDiscount(
                    $intProductID,
                    $intIBlockID,
                    array($priceData['CATALOG_GROUP_ID']),
                    $arUserGroups,
                    $renewal,
                    $siteID,
                    $arDiscountCoupons
                );

                $discountResult = \CCatalogDiscount::applyDiscountList($currentPrice, $resultCurrency, $arDiscounts);
                unset($arDiscounts);
                if ($discountResult === false)
                    return false;
                $result['PRICE'] = $discountResult['PRICE'];
                $result['COMPARE_PRICE'] = $discountResult['PRICE'];
                $result['DISCOUNT_LIST'] = $discountResult['DISCOUNT_LIST'];
                unset($discountResult);
            }

            if ($priceData['NO_VAT'] === 'N') {
                if (!$resultWithVat) {
                    $result['PRICE'] /= (1 + $priceData['VAT_RATE']);
                    $result['COMPARE_PRICE'] /= (1 + $priceData['VAT_RATE']);
                    $result['BASE_PRICE'] /= (1 + $priceData['VAT_RATE']);
                }
            }

            $result['UNROUND_PRICE'] = $result['PRICE'];
            $result['UNROUND_BASE_PRICE'] = $result['BASE_PRICE'];
            if (Catalog\Product\Price\Calculation::isComponentResultMode()) {
                $result['BASE_PRICE'] = Catalog\Product\Price::roundPrice(
                    $priceData['CATALOG_GROUP_ID'],
                    $result['BASE_PRICE'],
                    $resultCurrency
                );
                $result['PRICE'] = Catalog\Product\Price::roundPrice(
                    $priceData['CATALOG_GROUP_ID'],
                    $result['PRICE'],
                    $resultCurrency
                );
                if (
                    empty($result['DISCOUNT_LIST'])
                    || Catalog\Product\Price\Calculation::compare($result['BASE_PRICE'], $result['PRICE'], '<=')
                ) {
                    $result['BASE_PRICE'] = $result['PRICE'];
                }
                $result['COMPARE_PRICE'] = $result['PRICE'];
            }

            if (empty($minimalPrice) || $minimalPrice['COMPARE_PRICE'] > $result['COMPARE_PRICE']) {
                $minimalPrice = $result;
            } elseif (
                $minimalPrice['COMPARE_PRICE'] == $result['COMPARE_PRICE']
                && $minimalPrice['RAW_PRICE']['PRICE_SCALE'] > $result['RAW_PRICE']['PRICE_SCALE']
            ) {
                $minimalPrice = $result;
            }

            unset($currentPrice, $result);
        }
        unset($priceData);
        unset($vat);

        $discountValue = ($minimalPrice['BASE_PRICE'] - $minimalPrice['PRICE']);

        if ($minimalPrice['RAW_PRICE']['NO_VAT'] === 'N') {
            $vatIncluded = $resultWithVat ? 'Y' : 'N';
        } else {
            $vatIncluded = 'N';
        }
        unset($minimalPrice['RAW_PRICE']['PRICE_SCALE']);
        $arResult = array(
            'PRICE' => $minimalPrice['RAW_PRICE'],
            'RESULT_PRICE' => array(
                'ID' => $minimalPrice['RAW_PRICE']['ID'],
                'PRICE_TYPE_ID' => $minimalPrice['RAW_PRICE']['CATALOG_GROUP_ID'],
                'BASE_PRICE' => $minimalPrice['BASE_PRICE'],
                'DISCOUNT_PRICE' => $minimalPrice['PRICE'],
                'CURRENCY' => $resultCurrency,
                'DISCOUNT' => $discountValue,
                'PERCENT' => (
                    $minimalPrice['BASE_PRICE'] > 0 && $discountValue > 0
                    ? round((100 * $discountValue) / $minimalPrice['BASE_PRICE'], 0)
                    : 0
                ),
                'VAT_RATE' => $minimalPrice['RAW_PRICE']['VAT_RATE'],
                'VAT_INCLUDED' => $vatIncluded,
                'NO_VAT' => $minimalPrice['RAW_PRICE']['NO_VAT'],
                'UNROUND_BASE_PRICE' => $minimalPrice['UNROUND_BASE_PRICE'],
                'UNROUND_DISCOUNT_PRICE' => $minimalPrice['UNROUND_PRICE']
            ),
            'DISCOUNT_PRICE' => $minimalPrice['PRICE'],
            'DISCOUNT' => array(),
            'DISCOUNT_LIST' => array(),
            'PRODUCT_ID' => $intProductID
        );
        if (!empty($minimalPrice['DISCOUNT_LIST'])) {
            reset($minimalPrice['DISCOUNT_LIST']);
            $arResult['DISCOUNT'] = current($minimalPrice['DISCOUNT_LIST']);
            $arResult['DISCOUNT_LIST'] = $minimalPrice['DISCOUNT_LIST'];
        }
        unset($minimalPrice);

        return $arResult;
    }

    /**
     * @param array $userGroups
     * @return array
     */
    private static function getAllowedPriceTypes(array $userGroups)
    {
        static $priceTypeCache = array();

        Main\Type\Collection::normalizeArrayValuesByInt($userGroups, true);
        if (empty($userGroups))
            return array();

        $cacheKey = 'U' . implode('_', $userGroups);
        if (!isset($priceTypeCache[$cacheKey])) {
            $priceTypeCache[$cacheKey] = array();
            $priceIterator = Catalog\GroupAccessTable::getList(array(
                'select' => array('CATALOG_GROUP_ID'),
                'filter' => array('@GROUP_ID' => $userGroups, '=ACCESS' => Catalog\GroupAccessTable::ACCESS_BUY),
                'order' => array('CATALOG_GROUP_ID' => 'ASC')
            ));
            while ($priceType = $priceIterator->fetch()) {
                $priceTypeId = (int)$priceType['CATALOG_GROUP_ID'];
                $priceTypeCache[$cacheKey][$priceTypeId] = $priceTypeId;
                unset($priceTypeId);
            }
            unset($priceType, $priceIterator);
        }

        return $priceTypeCache[$cacheKey];
    }
}
