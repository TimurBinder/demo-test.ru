<?php

namespace Redsign\DevFunc\Sale\Location;

use Bitrix\Currency;
use Bitrix\Main\Context;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Cookie;
use Bitrix\Main\Web\Uri;
use Bitrix\Sale;
use Redsign\DevFunc\Sale\Location\Location;

class Region
{
    public const COOKIE_NAME = 'current_region';

    public static function isCliMode(): bool
    {
        return PHP_SAPI === 'cli';
    }

    public static function OnPageStart(): void
    {
        if (
            defined('ADMIN_SECTION')
            || defined('WIZARD_SITE_ID')
            || self::isCliMode()
            || !self::isUseRegionality()
        ) {
            return;
        }

        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
        $locationId = (int) $request->get('location');

        $userRegion = [];

        $savedRegion = self::get();

        $userRegion = $savedRegion
            ? self::getById($savedRegion['ID'])
            : self::getByHost();

        if (empty($userRegion))
            $userRegion = self::getCurrentRegion();

        if (empty($userRegion))
            return;

        $server = Context::getCurrent()->getServer();
        $userLocation = Location::getMyCity();

        $domainList = is_array($userRegion['LIST_DOMAINS']) ? $userRegion['LIST_DOMAINS'] : [];

        if ($domainList && !in_array($server->getHttpHost(), $domainList)) {
            if ($userLocation['ID'] !== $userRegion['LOCATION_ID'])
                Location::setMyCity($userRegion['LOCATION_ID']);

            $uriString = $request->getRequestUri();

            $uri = new Uri($uriString);
            $uri->setHost(reset($domainList));

            LocalRedirect($uri->getUri(), true);
        } elseif ($locationId > 0 && $userLocation['ID'] != $locationId) {
            Location::setMyCity($locationId);

            $uriString = $request->getRequestUri();
            $uri = new Uri($uriString);
            $uri->deleteParams(['location', 'region']);

            LocalRedirect($uri->getUri(), true);
        }

        if (empty($userLocation) && $userRegion['LOCATION_ID'] > 0)
            Location::setMyCity($userRegion['LOCATION_ID']);

        define('SITE_LOCATION_ID', $userRegion['LOCATION_ID']);

        $GLOBALS['arRegionFilter'] = [
            [
                'LOGIC' => 'OR',
                [
                    'PROPERTY_REGION_REF' => $userRegion['ID'],
                ],
                [
                    'PROPERTY_REGION_REF' => false,
                ]
            ]
        ];
    }

    public static function OnEndBufferContent(string &$content): void
    {
        if (defined('ADMIN_SECTION') || defined('WIZARD_SITE_ID') || !self::isUseRegionality())
            return;

        $userRegion = self::getCurrentRegion();

        if (!is_array($userRegion))
            return;

        // replace macros
        if (is_array($userRegion['REGION_MACROS']) && count($userRegion['REGION_MACROS']) > 0) {
            foreach ($userRegion['REGION_MACROS'] as $sMacros => $arValues) {
                if (!is_array($arValues))
                    continue;

                $placeholder = $sMacros ? (string)$sMacros : '';

                foreach ($arValues as $iValueKey => $sValue) {
                    if ($iValueKey != 0)
                        $placeholder .= '_' . $iValueKey;

                    $content = str_replace('#' . $placeholder . '#', $sValue, $content);
                }
            }
        }
    }

    public static function get(): ?array
    {
        /** @var \Bitrix\Main\HttpRequest */
        $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

        $arRegion = $request->getCookie(self::COOKIE_NAME);

        return $arRegion ? unserialize($arRegion) : null;
    }

    public static function getByHost(): ?array
    {
        $arRegionCurrent = [];

        $arRegions = self::getRegions();
        if ($arRegions) {
            $server = Context::getCurrent()->getServer();
            foreach ($arRegions as $arRegion) {
                if (in_array($server->getHttpHost(), $arRegion['LIST_DOMAINS'])) {
                    $arRegionCurrent = $arRegion;
                    break;
                }
            }
        }

        return $arRegionCurrent;
    }

    public static function getById(int $id = 0): ?array
    {
        $arRegionCurrent = null;

        $arRegions = self::getRegions();

        if (is_array($arRegions) && count($arRegions) > 0) {
            foreach ($arRegions as $arRegion) {
                if ($id == $arRegion['ID']) {
                    $arRegionCurrent = $arRegion;
                    break;
                }
            }
            unset($arRegion);
        }

        return $arRegionCurrent;
    }

    public static function set(array $arRegion = []): void
    {
        static $eventOnGetExists = null;

        if ($eventOnGetExists === true || $eventOnGetExists === null) {
            foreach (GetModuleEvents('redsign.devfunc', 'OnSiteRegionSelected', true) as $arEvent) {
                $eventOnGetExists = true;
                $mxResult = ExecuteModuleEventEx(
                    $arEvent,
                    [
                        $arRegion,
                    ]
                );
            }
            if ($eventOnGetExists === null)
                $eventOnGetExists = false;
        }

        $arCityData = [
            'ID' => $arRegion['ID'],
        ];

        $cookie = new Cookie(self::COOKIE_NAME, serialize($arCityData));

        /** @var \Bitrix\Main\HttpResponse $response */
        $response = Context::getCurrent()->getResponse();
        $response->addCookie($cookie);

        if (Loader::includeModule('sale')) {
            $basket = Sale\Basket::loadItemsForFUser(\CSaleBasket::GetBasketUserID(), SITE_ID);
            $basket->refreshData(array('PRICE', 'COUPONS'));
            $basket->save();
        }
    }

    public static function getRegionIBlockID(): int
    {
        return (int) Option::get('redsign.devfunc', 'location_region_iblock_id', '', SITE_ID);
    }


    public static function isUseRegionality(): bool
    {
        if (Option::get('redsign.devfunc', 'use_location_region', 'Y', SITE_ID) !== 'Y') {
            return false;
        }

        $iRegionIBlockID = self::getRegionIBlockID();

        if (intval($iRegionIBlockID) <= 0) {
            return false;
        }

        return true;
    }

    public static function getRegions(): array
    {
        static $arRegions;

        if (isset($arRegions))
            return $arRegions;

        $cacheTime = 86400;
        $cacheId = 'redsign_devfunc_regions';
        $cacheDir = '/' . __CLASS__ . '/' . __FUNCTION__ . '/';

        $cacheDir = str_replace('\\', '/', $cacheDir);

        $cache = \Bitrix\Main\Data\Cache::createInstance();

        if ($cache->initCache($cacheTime, $cacheId, $cacheDir)) {
            $res = $cache->getVars();
            $arRegions = $res['arRegions'];
        } elseif ($cache->startDataCache()) {
            $taggedCache = \Bitrix\Main\Application::getInstance()->getTaggedCache();
            $taggedCache->startTagCache($cacheDir);

            $arRegions = [];

            if ($iRegionIBlockID = self::getRegionIBlockID()) {
                if (Loader::includeModule('iblock')) {
                    $arSort = [
                        'PROPERTY_LOCATION_DEFAULT' => 'DESC',
                        'SORT' => 'ASC',
                    ];

                    $arFilter = [
                        'ACTIVE' => 'Y',
                        'IBLOCK_ID' => $iRegionIBlockID
                    ];
                    $arSelect = [
                        'ID',
                        'NAME',
                        'IBLOCK_ID',
                        'IBLOCK_SECTION_ID',
                        'DETAIL_TEXT',
                        'PROPERTY_*',
                    ];

                    $dbItems = \CIBlockElement::getList(
                        $arSort,
                        $arFilter,
                        false,
                        false,
                        $arSelect
                    );

                    while ($obItem = $dbItems->GetNextElement()) {
                        $arItem = $obItem->GetFields();
                        $arItem['PROPERTIES'] = $obItem->GetProperties();

                        foreach ($arItem['PROPERTIES'] as $sPropCode => $arProp) {
                            $prop = &$arItem['PROPERTIES'][$sPropCode];

                            if ($prop['VALUE'] && !is_array($prop['VALUE'])) {
                                // $prop['MULTIPLE'] = 'Y';
                                $prop['VALUE'] = (array) $prop['VALUE'];
                                // $prop['~VALUE'] = (array) $prop['~VALUE'];
                            }

                            if (
                                isset($prop["USER_TYPE"]) && !empty($prop["USER_TYPE"])
                                || $prop['PROPERTY_TYPE'] == 'S' && $prop['USER_TYPE'] == 'HTML'
                            ) {
                                $arItem['DISPLAY_PROPERTIES'][$sPropCode] = \CIBlockFormatProperties::GetDisplayValue(
                                    $arItem,
                                    $prop,
                                    'region_out'
                                );

                                if (!is_array($arItem['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'])) {
                                    $arItem['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'] =
                                        (array)$arItem['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'];
                                }
                            } elseif ($prop['PROPERTY_TYPE'] == 'F') {
                                $arItem['DISPLAY_PROPERTIES'][$sPropCode] = $prop;

                                if (
                                    is_array($arItem['DISPLAY_PROPERTIES'][$sPropCode]['VALUE'])
                                    && count($arItem['DISPLAY_PROPERTIES'][$sPropCode]['VALUE']) > 0
                                ) {
                                    foreach ($arItem['DISPLAY_PROPERTIES'][$sPropCode]['VALUE'] as $key => $value) {
                                        $arFile = \CFile::GetFileArray($value);
                                        $arItem['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'][$key] =
                                            '<img src="' . $arFile['SRC'] . '" alt="' . $arFile['DESCRIPTION'] . '">';
                                        unset($arFile);
                                    }
                                    unset($key, $value);
                                } else {
                                }
                            }
                        }
                        unset($sPropCode, $arProp);

                        $arRegions[$arItem['ID']] = $arItem;
                    }
                    unset($obItem, $arItem);
                }

                if ($arRegions) {
                    foreach ($arRegions as $key => $arRegion) {
                        if ($arRegion['PROPERTIES']['LOCATION_DEFAULT']['VALUE_XML_ID'] == 'yes') {
                            $arRegions[$key]['DEFAULT'] = true;
                        }
                        unset($arRegions[$key]['PROPERTIES']['LOCATION_DEFAULT']);

                        //domains props
                        if (!is_array($arRegion['PROPERTIES']['DOMAINS']['VALUE'])) {
                            $arRegion['PROPERTIES']['DOMAINS']['VALUE'] = !empty(
                                $arRegion['PROPERTIES']['DOMAINS']['VALUE']
                            )
                                ? (array)$arRegion['PROPERTIES']['DOMAINS']['VALUE']
                                : array();
                        }

                        if (
                            isset($arRegion['PROPERTIES']['MAIN_DOMAIN'])
                            && $arRegion['PROPERTIES']['MAIN_DOMAIN']['VALUE'] != ''
                            && !in_array(
                                $arRegion['PROPERTIES']['MAIN_DOMAIN']['VALUE'],
                                $arRegion['PROPERTIES']['DOMAINS']['VALUE']
                            )
                        ) {
                            $arRegions[$key]['LIST_DOMAINS'] = array_merge(
                                (array)$arRegion['PROPERTIES']['MAIN_DOMAIN']['VALUE'],
                                $arRegion['PROPERTIES']['DOMAINS']['VALUE']
                            );
                        } else {
                            $arRegions[$key]['LIST_DOMAINS'] = $arRegion['PROPERTIES']['DOMAINS']['VALUE'];
                        }
                        unset(
                            $arRegions[$key]['PROPERTIES']['DOMAINS'],
                            $arRegions[$key]['PROPERTIES']['MAIN_DOMAIN']
                        );

                        //stores props
                        if (!is_array($arRegion['PROPERTIES']['STORES_REF']['VALUE'])) {
                            $arRegion['PROPERTIES']['STORES_REF']['VALUE'] =
                                (array)$arRegion['PROPERTIES']['STORES_REF']['VALUE'];
                        }
                        $arRegions[$key]['LIST_STORES'] = $arRegion['PROPERTIES']['STORES_REF']['VALUE'];
                        unset($arRegions[$key]['PROPERTIES']['STORES_REF']);

                        //location props
                        $arRegions[$key]['LOCATION_ID'] = is_array($arRegion['PROPERTIES']['LOCATION_REF']['VALUE'])
                            ? reset($arRegion['PROPERTIES']['LOCATION_REF']['VALUE'])
                            : $arRegion['PROPERTIES']['LOCATION_REF']['VALUE'];

                        unset($arRegions[$key]['PROPERTIES']['LOCATION_REF']);

                        //prices props
                        if (Loader::includeModule('catalog')) {
                            if (!is_array($arRegion['PROPERTIES']['PRICES_REF']['VALUE'])) {
                                $arRegion['PROPERTIES']['PRICES_REF']['VALUE'] =
                                    (array)$arRegion['PROPERTIES']['PRICES_REF']['VALUE'];
                            }

                            if ($arRegion['PROPERTIES']['PRICES_REF']['VALUE']) {
                                if (reset($arRegion['PROPERTIES']['PRICES_REF']['VALUE']) != 'component') {
                                    $dbPriceType = \CCatalogGroup::GetList(
                                        array(
                                            'SORT' => 'ASC'
                                        ),
                                        array(
                                            'ID' => $arRegion['PROPERTIES']['PRICES_REF']['VALUE']
                                        ),
                                        false,
                                        false,
                                        array(
                                            'ID',
                                            'NAME',
                                            'CAN_BUY'
                                        )
                                    );

                                    while ($arPriceType = $dbPriceType->Fetch()) {
                                        $arRegions[$key]['LIST_PRICES'][$arPriceType['NAME']] = $arPriceType;
                                    }
                                } else {
                                    $arRegions[$key]['LIST_PRICES'] = $arRegion['PROPERTIES']['PRICES_REF']['VALUE'];
                                }
                            } else {
                                $arRegions[$key]['LIST_PRICES'] = array();
                            }
                            unset($arRegions[$key]['PROPERTIES']['PRICES_REF']);
                        }

                        foreach ($arRegion['PROPERTIES'] as $sPropkey => $arProp) {
                            if (strpos($sPropkey, 'REGION_') === 0) {
                                if (!is_array($arProp['VALUE'])) {
                                    $arProp['VALUE'] = (array)$arProp['VALUE'];
                                }

                                if (isset($arRegion['DISPLAY_PROPERTIES'][$sPropkey])) {
                                    $sMacrosInsert = $arRegion['DISPLAY_PROPERTIES'][$sPropkey]['DISPLAY_VALUE'];
                                    unset($arRegions[$key]['DISPLAY_PROPERTIES'][$sPropkey]);
                                } else {
                                    $sMacrosInsert = $arProp['VALUE'];
                                }

                                $arRegions[$key]['REGION_MACROS'][$sPropkey] = $sMacrosInsert;

                                if ($sPropkey == 'REGION_PHONE') {
                                    $arRegions[$key]['REGION_MACROS'][$sPropkey . '_URL'] = array_map(
                                        function ($v) {
                                            return preg_replace('/[^0-9\+]/', '', $v);
                                        },
                                        $sMacrosInsert
                                    );
                                }

                                unset($arRegions[$key]['PROPERTIES'][$sPropkey]);
                            }
                        }
                        unset($sPropkey, $arProp);
                    }
                }
            }

            $taggedCache->registerTag('iblock_id_' . $iRegionIBlockID);
            $taggedCache->endTagCache();

            if (empty($arRegions)) {
                $cache->abortDataCache();
            }

            $cache->endDataCache([
                'arRegions' => $arRegions
            ]);
        }

        return $arRegions;
    }

    public static function getCurrentRegion(): array
    {
        // static $arRegion;

        $arRegions = self::getRegions();
        $city = Location::getMyCity();

        if ($city) {
            foreach ($arRegions as $iRegionKey => $arRegion) {
                if ($city['ID'] === $arRegion['LOCATION_ID']) {
                    return $arRegions[$iRegionKey];
                }
            }

            foreach ($arRegions as $arRegion) {
                if ($city['NAME'] === $arRegion['NAME']) {
                    return $arRegion;
                }
            }
        }

        $server = Context::getCurrent()->getServer();

        foreach ($arRegions as $arRegion) {
            if (in_array($server->getHttpHost(), $arRegion['LIST_DOMAINS'])) {
                return $arRegion;
            }
        }

        $arRegionCurrent = self::getDefaultRegion();

        if ($arRegionCurrent)
            return $arRegionCurrent;

        return $arRegions ? reset($arRegions) : [];
    }

    public static function getDefaultRegion(): array
    {
        $arRegions = self::getRegions();

        if (is_array($arRegions) && count($arRegions) > 0) {
            foreach ($arRegions as $arRegion) {
                if ($arRegion['DEFAULT']) {
                    return $arRegion;
                }
            }
        }

        return [];
    }

    public static function getPricesFilter(): array
    {
        $arRegionCurrent = self::getCurrentRegion();

        $arFilterIds = array();

        if (
            array_key_exists('LIST_PRICES', $arRegionCurrent)
            && reset($arRegionCurrent['LIST_PRICES']) != 'component'
        ) {
            foreach ($arRegionCurrent['LIST_PRICES'] as $arPrice) {
                // TODO need group cache or
                // if ($arPrice['CAN_BUY'] == 'Y')
                // {
                    $arFilterIds[] = $arPrice['ID'];
                // }
            }
            unset($arPrice);
        }

        return $arFilterIds;
    }

    /**
     * @param int $intProductID
     * @param int|float $quantity
     * @param array $arUserGroups
     * @param string $renewal
     * @param array $priceList
     * @param bool|string $siteID
     * @param bool|array $arDiscountCoupons
     * @return array|bool
     */
    public static function OnGetOptimalPrice(
        $intProductID,
        $quantity = 1,
        $arUserGroups = [],
        $renewal = 'N',
        $priceList = [],
        $siteID = false,
        $arDiscountCoupons = false
    ) {
        $arRegion = self::getCurrentRegion();

        if ($arRegion) {
            return \Redsign\DevFunc\Catalog\Product::GetOptimalPrice(
                $intProductID,
                $quantity,
                $arUserGroups,
                $renewal,
                $priceList,
                $siteID,
                $arDiscountCoupons,
                $arRegion
            );
        } else {
            return true;
        }
    }

    public static function editCatalogResult(array &$arResult): void
    {
        if (!self::isUseRegionality())
            return;

        $arFilterIds = self::getPricesFilter();

        if (is_array($arFilterIds) && count($arFilterIds) > 0) {
            if (isset($arResult['CAT_PRICES'])) {
                if (is_array($arResult['CAT_PRICES'])) {
                    $arResult['CAT_PRICES'] = array_filter(
                        $arResult['CAT_PRICES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v['ID'], $arFilterIds);
                        }
                    );
                }

                if (is_array($arResult['PRICES'])) {
                    $arResult['PRICES'] = array_filter(
                        $arResult['PRICES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v['PRICE_ID'], $arFilterIds);
                        }
                    );
                }
            } else {
                if (is_array($arResult['PRICES'])) {
                    $arResult['PRICES'] = array_filter(
                        $arResult['PRICES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v['ID'], $arFilterIds);
                        }
                    );
                }
            }

            if (is_array($arResult['PRICES_ALLOW'])) {
                $arResult['PRICES_ALLOW'] = array_filter(
                    $arResult['PRICES_ALLOW'],
                    function ($v) use ($arFilterIds) {
                        return in_array($v, $arFilterIds);
                    }
                );
            }
        }
    }

    public static function editCatalogItem(array &$item): void
    {
        if (!self::isUseRegionality())
            return;

        $arFilterIds = self::getPricesFilter();

        if (is_array($arFilterIds) && count($arFilterIds) > 0) {
            self::filterItemPrices($item, $arFilterIds);

            if (is_array($item['OFFERS']) && count($item['OFFERS']) > 0) {
                $currency = '';
                if (Loader::includeModule('catalog')) {
                    $currency = Currency\CurrencyManager::getBaseCurrency();
                }

                $item['ITEM_START_PRICE'] = null;
                $item['ITEM_START_PRICE_SELECTED'] = null;

                $minPrice = null;
                $minPriceIndex = null;

                foreach ($item['OFFERS'] as $iOfferKey => $arOffer) {
                    self::filterItemPrices($item['OFFERS'][$iOfferKey], $arFilterIds);
                    $priceKey = $item['OFFERS'][$iOfferKey]['ITEM_PRICE_SELECTED'];

                    if (!$item['OFFERS'][$iOfferKey]['CAN_BUY'] || $priceKey === null)
                        continue;

                    $currentPrice = $item['OFFERS'][$iOfferKey]['ITEM_PRICES'][$priceKey];
                    if ($currentPrice['CURRENCY'] != $currency) {
                        $priceScale = \CCurrencyRates::ConvertCurrency(
                            $currentPrice['RATIO_PRICE'],
                            $currentPrice['CURRENCY'],
                            $currency
                        );
                    } else {
                        $priceScale = $currentPrice['RATIO_PRICE'];
                    }
                    if ($minPrice === null || $minPrice > $priceScale) {
                        $minPrice = $priceScale;
                        $minPriceIndex = $iOfferKey;
                    }
                    unset($priceScale, $currentPrice);
                }
                unset($iOfferKey, $arOffer);


                if ($minPriceIndex !== null) {
                    $minOffer = $item['OFFERS'][$minPriceIndex];
                    $item['ITEM_START_PRICE_SELECTED'] = $minPriceIndex;
                    $item['ITEM_START_PRICE'] = $minOffer['ITEM_PRICES'][$minOffer['ITEM_PRICE_SELECTED']];
                }
            }
        }
    }

    public static function editSmartFilterResult(array &$arResult): void
    {
        if (!self::isUseRegionality())
            return;

        $arFilterIds = self::getPricesFilter();

        if (is_array($arFilterIds) && count($arFilterIds) > 0) {
            if (is_array($arResult['PRICES'])) {
                $arFilterKeys = array_column(
                    array_filter(
                        $arResult['PRICES'],
                        function ($v) use ($arFilterIds) {
                            return !in_array($v['ID'], $arFilterIds);
                        }
                    ),
                    'CODE'
                );

                if (is_array($arFilterKeys) && count($arFilterKeys) > 0) {
                    if (is_array($arResult['COMBO']) && count($arResult['COMBO']) > 0) {
                        foreach ($arResult['COMBO'] as $key => $arValues) {
                            $arResult['COMBO'][$key] = array_diff_key(
                                $arValues,
                                array_flip($arFilterKeys)
                            );
                        }
                        unset($key, $arValues);
                    }
                }
                unset($arFilterKeys);

                $arResult['PRICES'] = array_filter(
                    $arResult['PRICES'],
                    function ($v) use ($arFilterIds) {
                        return in_array($v['ID'], $arFilterIds);
                    }
                );

                $arResult['ITEMS'] = array_filter(
                    $arResult['ITEMS'],
                    function ($v) use ($arFilterIds) {
                        return !isset($v['PRICE'])
                            || isset($v['PRICE']) && in_array($v['ID'], $arFilterIds);
                    }
                );
            }
        }
    }

    private static function filterItemPrices(array &$item, array $arFilterIds): void
    {
        if (is_array($item['PRICES_ALLOW'])) {
            $item['PRICES_ALLOW'] = array_filter(
                $item['PRICES_ALLOW'],
                function ($v) use ($arFilterIds) {
                    return in_array($v, $arFilterIds);
                }
            );
        }

        if (is_array($item['CAT_PRICES'])) {
            $item['CAT_PRICES'] = array_filter(
                $item['CAT_PRICES'],
                function ($v) use ($arFilterIds) {
                    return in_array($v['ID'], $arFilterIds);
                }
            );
        }

        // PRICES
        if (is_array($item['PRICES'])) {
            $item['PRICES'] = array_filter(
                $item['PRICES'],
                function ($v) use ($arFilterIds) {
                    return in_array($v['PRICE_ID'], $arFilterIds);
                }
            );

            $minimalPrice = null;
            $baseCurrency = Currency\CurrencyManager::getBaseCurrency();

            foreach ($item['PRICES'] as $priceRow) {
                $priceRow['PRICE_SCALE'] = \CCurrencyRates::ConvertCurrency(
                    $priceRow['PRICE'],
                    $priceRow['CURRENCY'],
                    $baseCurrency
                );

                if ($minimalPrice === null || $minimalPrice['PRICE_SCALE'] > $priceRow['PRICE_SCALE']) {
                    $minimalPrice = $priceRow;
                }
            }
            unset($priceRow);

            if (is_array($minimalPrice)) {
                foreach ($item['PRICES'] as $iPricesKey => $arPrice) {
                    if ($arPrice['PRICE_ID'] == $minimalPrice['PRICE_ID']) {
                        $item['PRICES'][$iPricesKey]['MIN_PRICE'] = 'Y';
                    } else {
                        unset($item['PRICES'][$iPricesKey]['MIN_PRICE']);
                    }
                }
                unset($iPricesKey, $arPrice);
            }

            // MIN_PRICE
            $item['MIN_PRICE'] = \CIBlockPriceTools::getMinPriceFromList($item['PRICES']);
        }

        if (
            $item['CATALOG_TYPE'] == \CCatalogProduct::TYPE_PRODUCT
            || $item['CATALOG_TYPE'] == \CCatalogProduct::TYPE_SET
        ) {
            if (isset($item['MIN_PRICE']) && !empty($item['MIN_PRICE']) && isset($item['CATALOG_MEASURE_RATIO'])) {
                \CIBlockPriceTools::setRatioMinPrice($item, false);
            } else {
                unset($item['RATIO_PRICE']);
            }

            $item['MIN_BASIS_PRICE'] = $item['MIN_PRICE'];
        }

        // ITEM_ALL_PRICES
        if (is_array($item['ITEM_ALL_PRICES'])) {
            foreach ($item['ITEM_ALL_PRICES'] as $iPricesKey => $arPrices) {
                if (is_array($arPrices['PRICES'])) {
                    $item['ITEM_ALL_PRICES'][$iPricesKey]['PRICES'] = array_filter(
                        $item['ITEM_ALL_PRICES'][$iPricesKey]['PRICES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v['PRICE_TYPE_ID'], $arFilterIds);
                        }
                    );
                }
            }
            unset($iPricesKey, $arPrices);
        }

        // ITEM_PRICES
        if (is_array($item['ITEM_PRICES'])) {
            $item['ITEM_PRICES'] = array_filter(
                $item['ITEM_PRICES'],
                function ($v) use ($arFilterIds) {
                    return in_array($v['PRICE_TYPE_ID'], $arFilterIds);
                }
            );
        }

        if (
            is_array($item['ITEM_PRICES']) && count($item['ITEM_PRICES']) < 1
            && is_array($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES'])
            && count($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES']) > 0
        ) {
            $minimalPrice = null;
            $baseCurrency = Currency\CurrencyManager::getBaseCurrency();

            foreach ($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES'] as $priceRow) {
                $priceRow['PRICE_SCALE'] = \CCurrencyRates::ConvertCurrency(
                    $priceRow['PRICE'],
                    $priceRow['CURRENCY'],
                    $baseCurrency
                );

                if ($minimalPrice === null || $minimalPrice['PRICE_SCALE'] > $priceRow['PRICE_SCALE']) {
                    $minimalPrice = $priceRow;
                }
            }
            unset($priceRow);

            if (is_array($minimalPrice)) {
                foreach ($item['ITEM_ALL_PRICES'] as $iPricesKey => $arPrices) {
                    foreach ($arPrices['PRICES'] as $arPrice) {
                        if ($arPrice['PRICE_TYPE_ID'] == $minimalPrice['PRICE_TYPE_ID']) {
                            $arPrice['MIN_QUANTITY'] = $arPrices['MIN_QUANTITY'];
                            $arPrice['QUANTITY_FROM'] = $arPrices['QUANTITY_FROM'];
                            $arPrice['QUANTITY_TO'] = $arPrices['QUANTITY_TO'];
                            $arPrice['QUANTITY_HASH'] = $arPrices['QUANTITY_HASH'];
                            $arPrice['MEASURE_RATIO_ID'] = $arPrices['MEASURE_RATIO_ID'];

                            $item['ITEM_PRICES'][] = $arPrice;
                        }
                    }
                    unset($arPrice);
                }
                unset($iPricesKey, $arPrices);
            }
        }

        $arFilterKeys = array(
            'CATALOG_PRICE_',
            'CATALOG_GROUP_ID_',
            'CATALOG_GROUP_NAME_',
            'CATALOG_CAN_ACCESS_',
            'CATALOG_CAN_BUY_',
            'CATALOG_PRICE_ID_',
            'CATALOG_CURRENCY_',
            'CATALOG_QUANTITY_FROM_',
            'CATALOG_QUANTITY_TO_',
            'CATALOG_EXTRA_ID_',
        );
        foreach ($item as $k => $v) {
            if (preg_match('/^~?(' . implode('|', $arFilterKeys) . ')(\d+)$/', $k, $matches)) {
                if (!in_array($matches[2], $arFilterIds)) {
                    unset($item[$matches[0]]);
                }
            }
        }
        unset($k, $v);

        // PRICE_MATRIX
        if (is_array($item['PRICE_MATRIX'])) {
            if (is_array($item['PRICE_MATRIX']['COLS']) && count($item['PRICE_MATRIX']['COLS']) > 0) {
                $item['PRICE_MATRIX']['COLS'] = array_filter(
                    $item['PRICE_MATRIX']['COLS'],
                    function ($v) use ($arFilterIds) {
                        return in_array($v['ID'], $arFilterIds);
                    }
                );
            }

            if (is_array($item['PRICE_MATRIX']['MATRIX']) && count($item['PRICE_MATRIX']['MATRIX']) > 0) {
                $item['PRICE_MATRIX']['MATRIX'] = array_filter(
                    $item['PRICE_MATRIX']['MATRIX'],
                    function ($k) use ($arFilterIds) {
                        return in_array($k, $arFilterIds);
                    },
                    ARRAY_FILTER_USE_KEY
                );
            }

            if (is_array($item['PRICE_MATRIX']['CAN_BUY']) && count($item['PRICE_MATRIX']['CAN_BUY']) > 0) {
                $item['PRICE_MATRIX']['CAN_BUY'] = array_filter(
                    $item['PRICE_MATRIX']['CAN_BUY'],
                    function ($v) use ($arFilterIds) {
                        return in_array($v, $arFilterIds);
                    }
                );
            }
        }

        // CAN_BUY
        if (isset($item['ITEM_ALL_PRICES'])) {
            // $item['CAN_BUY'] = !empty($item['ITEM_PRICES']) && $item['PRODUCT']['AVAILABLE'] === 'Y';
        } else {
            if (is_array($item['PRICE_MATRIX'])) {
                // $item['CAN_BUY'] = is_array($item['PRICE_MATRIX']) && count($item['PRICE_MATRIX']) > 0;
            } elseif (is_array($item['PRICES']) && count($item['PRICES']) > 0) {
                // $item['CAN_BUY'] = false;
                foreach ($item['PRICES'] as $arPrice) {
                    if ($arPrice['CAN_BUY'] == 'Y') {
                        $item['CAN_BUY'] = true;
                        break;
                    }
                }
                unset($arPrice);
                // $item['CAN_BUY'] = \CIBlockPriceTools::CanBuy($item['IBLOCK_ID'], $item['PRICES'], $item);
            }
        }
    }

    public static function editCatalogStores(array &$item): void
    {
        if (!self::isUseRegionality())
            return;

        $arRegion = self::getCurrentRegion();

        if ($arRegion) {
            if ($arRegion['LIST_STORES'] && reset($arRegion['LIST_STORES']) != 'component') {
                $arFilterIds = $arRegion['LIST_STORES'];

                if (is_array($item['STORES'])) {
                    $item['STORES'] = array_filter(
                        $item['STORES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v['ID'], $arFilterIds);
                        }
                    );
                }

                if (is_array($item['JS']['STORES'])) {
                    $item['JS']['STORES'] = array_filter(
                        $item['JS']['STORES'],
                        function ($v) use ($arFilterIds) {
                            return in_array($v, $arFilterIds);
                        }
                    );
                }

                if (is_array($item['JS']['SKU']) && count($item['JS']['SKU']) > 0) {
                    foreach ($item['JS']['SKU'] as $iSkuKey => $arSku) {
                        $item['JS']['SKU'][$iSkuKey] = array_filter(
                            $item['JS']['SKU'][$iSkuKey],
                            function ($k) use ($arFilterIds) {
                                return in_array($k, $arFilterIds);
                            },
                            ARRAY_FILTER_USE_KEY
                        );
                    }
                }
            }
        }
    }
}
