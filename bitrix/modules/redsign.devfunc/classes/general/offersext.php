<?php

use Bitrix\Iblock;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;

Loc::loadMessages(__FILE__);


class RSDevFuncOffersExtension
{
    protected static bool $highLoadInclude = false;

    public static function __callStatic(string $name, array $arg): ?array
    {
        if ($name == 'GetAllPictures') {
            if (is_array($arg[0]) && is_array($arg[1]) && is_array($arg[2])) {
                return self::getPictures($arg[0], $arg[1], $arg[2]);
            } elseif (is_array($arg[0]) && is_array($arg[1]) && is_string($arg[2]) && is_string($arg[3]) && is_string($arg[4])) {
                return self::getPictures($arg[0], $arg[1], array('MORE_PHOTO_CODE' => $arg[2], 'SKU_MORE_PHOTO_CODE' => $arg[3], 'FIRST_PIC_FROM_FIRST_SKU' => $arg[4]));
            }
        }

        return null;
    }

    // get jsonData for SKU
    public static function GetJSONElement(array $arElement, array $arProps = [], array $arPrices = [], array $params = []): array
    {
        $arrReturn = array();
        $arElementsIDs = array($arElement['ID']);
        $ELEMENT_ID = $arElement['ID'];
        $arrElement = array();

        $defaultParams = array(
            'SIZES' => array('WIDTH' => '200', 'HEIGHT' => '150'),
            'MORE_PHOTO_CODE' => 'MORE_PHOTO',
            'SKU_MORE_PHOTO_CODE' => 'MORE_PHOTO',
            'SKU_ARTICLE_CODE' => 'CML2_ARTICLE',
        );

        foreach ($defaultParams as $key => $value) {
            if (!array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }

        $arSizes = $params['SIZES'];
        $arOffersJs = [];
        $arrSortProps = [];

        if (is_array($arElement['OFFERS']) && count($arElement['OFFERS']) > 0) {
            foreach ($arElement['OFFERS'] as $iOfferKey => $arOffer) {
                // USE_PRICE_COUNT fix
                if (!isset($arElementsIDs[$arOffer['ID']])) {
                    $arElementsIDs[$arOffer['ID']] = $iOfferKey;
                } else {
                    if (isset($arElement['OFFERS_SELECTED']) && $arElement['OFFERS_SELECTED'] == $iOfferKey) {
                        $arElement['OFFERS_SELECTED'] = $arElementsIDs[$arOffer['ID']];
                    }
                    unset($arElement['OFFERS'][$iOfferKey]);
                    continue;
                }

                // offer
                $arOfferJs = array(
                    'ID' => $arOffer['ID'],
                    'NAME' => $arOffer['NAME'],
                    'IMAGES' => self::getPictures(
                        $arSizes,
                        $arOffer,
                        array(
                            'MORE_PHOTO_CODE' => $params['SKU_MORE_PHOTO_CODE']
                        )
                    ),
                    'PROPERTIES' => array(),
                    'DISPLAY_PROPERTIES' => $arOffer['DISPLAY_PROPERTIES'],
                    'PRICES' => array(),
                    'CAN_BUY' => $arOffer['CAN_BUY'],
                    'ADD_URL' => $arOffer['ADD_URL'],
                    'CATALOG_MEASURE_RATIO' => $arOffer['CATALOG_MEASURE_RATIO'],
                    'CATALOG_MEASURE_NAME' => $arOffer['CATALOG_MEASURE_NAME'],
                );
                // article
                if ($arOffer['PROPERTIES'][$params['SKU_ARTICLE_CODE']]['VALUE'] != '') {
                    $arOfferJs['ARTICLE'] = $arOffer['PROPERTIES'][$params['SKU_ARTICLE_CODE']]['VALUE'];
                }
                // properties
                foreach ($arProps as $propCode) {
                    if ($arOffer['DISPLAY_PROPERTIES'][$propCode]['DISPLAY_VALUE'] != '') {
                        if (!in_array($propCode, $arrSortProps))
                            $arrSortProps[] = $propCode;
                        $arOfferJs['PROPERTIES'][$propCode] = $arOffer['DISPLAY_PROPERTIES'][$propCode]['DISPLAY_VALUE'];
                    }
                }

                // prices
                if ($params['USE_PRICE_COUNT'] && is_array($arOffer['PRICE_MATRIX']['COLS'])) {
                    RSDevFunc::getPriceMatrixEx($arOffer, 0, $params['FILTER_PRICE_TYPES'], 'Y', $params['CURRENCY_PARAMS']);

                    if (isset($arOffer['PRICE_MATRIX'])) {
                        $arOfferJs['PRICE_MATRIX'] = $arOffer['PRICE_MATRIX'];
                        $arOfferJs['PRICES'] = false;
                    }
                } else {
                    foreach ($arPrices as $priceCode) {
                        if (isset($arOffer['PRICES'][$priceCode])) {
                            $arOfferJs['PRICES'][$priceCode] = array(
                                'PRICE_ID' => $arOffer['PRICES'][$priceCode]['PRICE_ID'],
                                'VALUE' => $arOffer['PRICES'][$priceCode]['VALUE'],
                                'PRINT_VALUE' => $arOffer['PRICES'][$priceCode]['PRINT_VALUE'],
                                'DISCOUNT_VALUE' => $arOffer['PRICES'][$priceCode]['DISCOUNT_VALUE'],
                                'PRINT_DISCOUNT_VALUE' => $arOffer['PRICES'][$priceCode]['PRINT_DISCOUNT_VALUE'],
                                'DISCOUNT_DIFF' => $arOffer['PRICES'][$priceCode]['DISCOUNT_DIFF'],
                                'PRINT_DISCOUNT' => $arOffer['PRICES'][$priceCode]['PRINT_DISCOUNT_DIFF'],
                                'DISCOUNT_DIFF_PERCENT' => $arOffer['PRICES'][$priceCode]['DISCOUNT_DIFF_PERCENT'],
                            );
                        }

                        if (isset($arOffer['MIN_PRICE'])) {
                            $arOfferJs['MIN_PRICE'] = $arOffer['MIN_PRICE'];
                        }
                        $arOfferJs['PRICE_MATRIX'] = false;
                    }
                }

                $arOfferJs['CATALOG_SUBSCRIBE'] = ($arOffer['CATALOG_SUBSCRIBE'] == 'Y');

                // add ratio min price
                \CIBlockPriceTools::setRatioMinPrice($arOffer, false);

                $arOffersJs[$arOffer['ID']] = $arOfferJs;
                //RSDevFunc::GetDataForProductItem($arOffersJs,$params);
            }

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            $iTime = ConvertTimeStamp(time(), 'FULL');
            // add quickbuy
            if (CModule::IncludeModule('redsign.quickbuy')) {
                $arFilter = array(
                    'DATE_FROM' => $iTime,
                    'DATE_TO' => $iTime,
                    'QUANTITY' => 0,
                    'ELEMENT_ID' => array_keys($arElementsIDs),
                );
                $dbRes = CRSQUICKBUYElements::GetList(array('ID' => 'SORT'), $arFilter);
                while ($arData = $dbRes->Fetch()) {
                    if ($arData['ELEMENT_ID'] == $ELEMENT_ID) {
                        $arrElement['QUICKBUY'] = $arData;
                        $arrElement['QUICKBUY']['TIMER'] = CRSQUICKBUYMain::GetTimeLimit($arData);
                    } elseif (isset($arOffersJs[$arData['ELEMENT_ID']])) {
                        $arOffersJs[$arData['ELEMENT_ID']]['QUICKBUY'] = $arData;
                        $arOffersJs[$arData['ELEMENT_ID']]['QUICKBUY']['TIMER'] = CRSQUICKBUYMain::GetTimeLimit($arData);
                    }
                }
            }
            // /add quickbuy
            // add da2
            if (CModule::IncludeModule('redsign.daysarticle2')) {
                $arFilter = array(
                    'DATE_FROM' => $iTime,
                    'DATE_TO' => $iTime,
                    'QUANTITY' => 0,
                    'ELEMENT_ID' => array_keys($arElementsIDs),
                );
                $dbRes = CRSDA2Elements::GetList(array('ID' => 'SORT'), $arFilter);
                while ($arData = $dbRes->Fetch()) {
                    if ($arData['ELEMENT_ID'] == $ELEMENT_ID) {
                        $arrElement['DAYSARTICLE2'] = $arData;
                        $arrElement['DAYSARTICLE2']['DINAMICA_EX'] = CRSDA2Elements::GetDinamica($arData);
                    } elseif (isset($arOffersJs[$arData['ELEMENT_ID']])) {
                        $arOffersJs[$arData['ELEMENT_ID']]['DAYSARTICLE2'] = $arData;
                        $arOffersJs[$arData['ELEMENT_ID']]['DAYSARTICLE2']['DINAMICA_EX'] = CRSDA2Elements::GetDinamica($arData);
                    }
                }
            }
        }

        if ($params['USE_PRICE_COUNT']) {
            RSDevFunc::getPriceMatrixEx($arElement, 0, $params['FILTER_PRICE_TYPES'], 'Y', $params['CURRENCY_PARAMS']);
            $arrElement['PRICE_MATRIX'] = $arElement['PRICE_MATRIX'];

            // add ratio min price
            CIBlockPriceTools::setRatioMinPrice($arElement, false);
        }

        $arrReturn = array(
            'ELEMENT' => $arrElement,
            'SORT_PROPS' => $arrSortProps,
            'OFFERS' => $arOffersJs,
        );

        return $arrReturn;
    }

    // get sorted properties
    public static function GetSortedProperties(array $arOffers, array $arProps = [], array $params = []): array
    {
        if (!isset($params['OFFERS_SELECTED'])) {
            $params['OFFERS_SELECTED'] = 0;
        }

        $arKeys = [];
        $arProperties = [];
        $arPropsData = [];


        if (
            Loader::includeModule('iblock') &&
            is_array($arOffers) && count($arOffers) > 0 &&
            is_array($arProps) && count($arProps) > 0 &&
            intval($arOffers[$params['OFFERS_SELECTED']]['IBLOCK_ID']) > 0
        ) {
            $separator = '__';
            $arParamsProps = $arProps;
            $arProps = array();
            $arrPropData = array();
            $propRes = CIBlockProperty::GetList(
                array(
                    'SORT' => 'ASC',
                    'ID' => 'ASC'
                ),
                array(
                    'IBLOCK_ID' => $arOffers[$params['OFFERS_SELECTED']]['IBLOCK_ID'],
                    'ACTIVE' => 'Y',
                    'MULTIPLE' => 'N'
                )
            );

            while ($propInfo = $propRes->Fetch()) {
                if (
                    in_array($propInfo['CODE'], $arParamsProps)
                    && isset($arOffers[$params['OFFERS_SELECTED']]['DISPLAY_PROPERTIES'][$propInfo['CODE']])
                ) {
                    if (isset($arOffers[$params['OFFERS_SELECTED']]['DISPLAY_PROPERTIES'][$propInfo['CODE']])) {
                        $arProps[] = $propInfo['CODE'];
                    }
                    $arPropData = $propInfo;

                    if ($arPropData['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && $arPropData['USER_TYPE'] == 'directory') {
                        RSDevFunc::getHighloadBlockValues($arPropData);
                    } elseif ($arPropData['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST) {
                        // list
                        $arPropData['VALUES'] = self::GetSortedPropertiesValues($arPropData);
                    }

                    $arrPropData[$propInfo['CODE']] = $arPropData;
                }
            }

            // prepare properties
            $arrForFirst = [];
            foreach ($arOffers as $key1 => $arOffer) {
                $compilKey = [];
                if (is_array($arOffer['DISPLAY_PROPERTIES']) && count($arOffer['DISPLAY_PROPERTIES']) > 0) {
                    foreach ($arProps as $propCode) {
                        $arPropData = $arrPropData[$propCode];
                        $arOfferProperty = $arOffer['DISPLAY_PROPERTIES'][$propCode];

                        $arPropertyValue = [
                            'FIRST_OFFER' => 'N',
                            'DISABLED_FOR_FIRST' => 'Y',
                        ];

                        if ($arOfferProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && $arOfferProperty['USER_TYPE'] == 'directory') {
                            $sValueIdField = 'VALUE';
                            $sValueId = $arOfferProperty[$sValueIdField];

                            foreach ($arrPropData[$propCode]['VALUES'] as $arValue) {
                                if ($arOfferProperty[$sValueIdField] == $arValue['XML_ID']) {
                                    $arPropertyValue['PICT'] = $arValue['PICT'];
                                    break;
                                }
                            }
                        } elseif ($arOfferProperty['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST) {
                            $sValueIdField = 'VALUE_ENUM_ID';
                            $sValueId = $arOfferProperty[$sValueIdField];
                        } else {
                            $sValueIdField = 'DISPLAY_VALUE';
                            $sValueId = $arOfferProperty[$sValueIdField];
                        }

                        $arOffers[$key1]['DISPLAY_PROPERTIES'][$propCode]['VALUE_ID'] = $arPropertyValue['VALUE_ID'] = $sValueId;
                        $arPropertyValue['VALUE'] = $arOfferProperty['DISPLAY_VALUE'];

                        if ($arOfferProperty[$sValueIdField] === $arOffers[$params['OFFERS_SELECTED']]['DISPLAY_PROPERTIES'][$propCode][$sValueIdField]) {
                            $arPropertyValue['FIRST_OFFER'] = 'Y';
                            $arrForFirst[$propCode] = $arPropertyValue['VALUE_ID'];
                        }

                        $arProperties[$propCode][$arOfferProperty[$sValueIdField]] = $arPropertyValue;

                        $compilKey[] = $arOfferProperty['DISPLAY_VALUE'];

                        if (empty($arPropsData[$propCode])) {
                            $arPropsData[$propCode] = array(
                                'ID' => $arOfferProperty['ID'],
                                'NAME' => $arOfferProperty['NAME'],
                                'CODE' => $arOfferProperty['CODE'],
                                'HINT' => $arOfferProperty['HINT'],
                            );
                        }
                    }
                    $compilKeyStr = implode($separator, $compilKey);
                    $arKeys[$compilKeyStr] = array(
                        'KEY' => $key1,
                        'OFFER_ID' => $arOffer['ID'],
                    );
                }
            }

            // sort properties
            if (is_array($params['PROP_FOR_SORT']) && count($params['PROP_FOR_SORT']) > 0) {
                foreach ($arProperties as $code => $arProperty) {
                    if (in_array($code, $params['PROP_FOR_SORT'])) {
                        ksort($arProperty);
                        $arProperties[$code] = $arProperty;
                    }
                }
            }

            // set enabled props for first offer
            $arrProps = [];
            $arrEnables = [];
            foreach ($arProps as $key1 => $propCode) {
                if ($key1 != (count($arProps) - 1)) {
                    $arrProps[] = $propCode;
                    $next_code = $arProps[($key1 + 1)];

                    foreach ($arOffers as $key2 => $arOffer) {
                        $all_prop_true2 = true;
                        foreach ($arrProps as $key3 => $propCode2) {
                            if ($arOffer['DISPLAY_PROPERTIES'][$propCode2]['VALUE_ID'] != $arrForFirst[$propCode2]) {
                                $all_prop_true2 = false;
                                break;
                            }
                        }

                        if ($all_prop_true2) {
                            if (!is_array($arrEnables[$next_code])) {
                                $arrEnables[$next_code] = [];
                            }

                            if (!in_array($arOffer['DISPLAY_PROPERTIES'][$next_code]['VALUE_ID'], $arrEnables[$next_code])) {
                                $arrEnables[$next_code][] = $arOffer['DISPLAY_PROPERTIES'][$next_code]['VALUE_ID'];
                            }
                        }
                    }
                }
            }

            $index = 0;
            foreach ($arProperties as $code => $arProperty) {
                if ($index == 0) {
                    foreach ($arProperty as $key1 => $arProp) {
                        $arProperties[$code][$key1]['DISABLED_FOR_FIRST'] = 'N';
                    }
                } else {
                    if (is_array($arrEnables)) {
                        foreach ($arProperty as $key1 => $arProp) {
                            if (in_array($arProp['VALUE_ID'], $arrEnables[$code])) {
                                $arProperties[$code][$key1]['DISABLED_FOR_FIRST'] = 'N';
                            }
                        }
                    }
                }
                $index++;
            }
        }

        return [
            'PROPS' => $arPropsData,
            'PROPERTIES' => $arProperties,
            'KEYS' => $arKeys,
        ];
    }

    // "in_array" replacement
    public static function inArray(string $find = '', array $array = []): bool
    {
        $return = false;
        if (is_array($array) && count($array) > 0 && $find != '') {
            foreach ($array as $val) {
                if ((string)$val === (string)$find) {
                    $return = true;
                    break;
                }
            }
        }
        return $return;
    }

    // get values for list and directory
    public static function GetSortedPropertiesValues(array $arProp): array
    {
        $arrReturn = [];
        $values = [];
        $valuesExist = false;
        static $PROP_CACHE = [];

        if (isset($PROP_CACHE[$arProp['ID']])) {
            // get from cache
            $arrReturn = $PROP_CACHE[$arProp['ID']];
        } else {
            // get from db
            if ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_STRING && $arProp['USER_TYPE'] == 'directory') {
                // highloadblock
                $pictMode = (isset($arProp['USER_TYPE_SETTINGS']['FIELDS_MAP']['UF_FILE']) ? true : false);

                if (!self::$highLoadInclude)
                    self::$highLoadInclude = Loader::includeModule('highloadblock');

                if (!self::$highLoadInclude)
                    throw new SystemException('Module highloadblock not installed');

                $xmlMap = array();
                $sortExist = isset($arProp['USER_TYPE_SETTINGS']['FIELDS_MAP']['UF_SORT']);
                $directorySelect = array('ID', 'UF_NAME', 'UF_XML_ID');
                $directoryOrder = array();
                if ($pictMode) {
                    $directorySelect[] = 'UF_FILE';
                }
                if ($sortExist) {
                    $directorySelect[] = 'UF_SORT';
                    $directoryOrder['UF_SORT'] = 'ASC';
                }
                $directoryOrder['UF_NAME'] = 'ASC';
                $sortValue = 100;
                $entityDataClass = $arProp['USER_TYPE_SETTINGS']['ENTITY']->getDataClass();
                $entityGetList = array(
                    'select' => $directorySelect,
                    'order' => $directoryOrder
                );
                $propEnums = $entityDataClass::getList($entityGetList);
                while ($oneEnum = $propEnums->fetch()) {
                    $oneEnum['ID'] = intval($oneEnum['ID']);
                    $oneEnum['UF_SORT'] = ($sortExist ? intval($oneEnum['UF_SORT']) : $sortValue);
                    $sortValue += 100;
                    if ($pictMode) {
                        if (!empty($oneEnum['UF_FILE'])) {
                            $arFile = CFile::GetFileArray($oneEnum['UF_FILE']);
                            if (!empty($arFile)) {
                                $oneEnum['PICT'] = array(
                                    'FILE_ID' => $oneEnum['UF_FILE'],
                                    'SRC' => $arFile['SRC'],
                                    'WIDTH' => intval($arFile['WIDTH']),
                                    'HEIGHT' => intval($arFile['HEIGHT'])
                                );
                            }
                        }

                        if (empty($oneEnum['PICT']))
                            $oneEnum['PICT'] = $arProp['DEFAULT_VALUES']['PICT'];
                    }
                    $values[$oneEnum['ID']] = array(
                        'ID' => $oneEnum['ID'],
                        'NAME' => $oneEnum['UF_NAME'],
                        'SORT' => intval($oneEnum['UF_SORT']),
                        'XML_ID' => $oneEnum['UF_XML_ID'],
                        'PICT' => ($pictMode ? $oneEnum['PICT'] : false)
                    );
                    $valuesExist = true;
                    $xmlMap[$oneEnum['UF_XML_ID']] = $oneEnum['ID'];
                }
            } elseif ($arProp['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST) {
                // list
                $propEnums = CIBlockProperty::GetPropertyEnum($arProp['ID'], array('SORT' => 'ASC', 'VALUE' => 'ASC'));
                while ($oneEnum = $propEnums->Fetch()) {
                    $oneEnum['ID'] = intval($oneEnum['ID']);
                    $values[$oneEnum['ID']] = array(
                        'ID' => $oneEnum['ID'],
                        'NAME' => $oneEnum['VALUE'],
                        'SORT' => intval($oneEnum['SORT']),
                        'XML_ID' => $oneEnum['XML_ID'],
                        'PICT' => false
                    );
                    $valuesExist = true;
                }
            }

            if ($valuesExist) {
                $PROP_CACHE[$arProp['ID']] = $values;
                $arrReturn = $values;
            }
        }

        return $arrReturn;
    }

    // get full pictures list
    public static function getPictures(array $arSizes = [], array $arElement = [], array $params = []): array
    {
        $defaultParams = array(
            'MORE_PHOTO_CODE' => 'MORE_PHOTO',
            'SKU_MORE_PHOTO_CODE' => 'MORE_PHOTO',
            'FIRST_PIC_FROM_FIRST_SKU' => 'N',
            'NO_SKU' => 'N',
            'PAGE' => 'section',
        );
        foreach ($defaultParams as $key => $value) {
            if (!array_key_exists($key, $params)) {
                $params[$key] = $value;
            }
        }
        $arrReturn = array();
        $arOffers = $arElement['OFFERS'];
        $first_pic = 0;

        if ($params['NO_SKU'] == 'N' && $params['FIRST_PIC_FROM_FIRST_SKU'] == 'Y' && is_array($arOffers) && count($arOffers) > 0) {
            if (!empty($arOffers[0]['PREVIEW_PICTURE']) && $params['PAGE'] != 'element') {
                $first_pic = $arOffers[0]['PREVIEW_PICTURE'];
                $arrReturn[] = CFile::ResizeImageGet($arOffers[0]['PREVIEW_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
            } elseif (!empty($arOffers[0]['DETAIL_PICTURE'])) {
                $first_pic = $arOffers[0]['DETAIL_PICTURE'];
                $arrReturn[] = CFile::ResizeImageGet($arOffers[0]['DETAIL_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
            } elseif ($params['SKU_MORE_PHOTO_CODE'] != '' && intval($arOffers[0]['PROPERTIES'][$params['SKU_MORE_PHOTO_CODE']]['VALUE'][0]) > 0) {
                $first_pic = $arOffers[0]['PROPERTIES'][$params['SKU_MORE_PHOTO_CODE']]['VALUE'][0];
                $arrReturn[] = CFile::ResizeImageGet($first_pic, array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true);
            }
        }
        if (!empty($arElement['PREVIEW_PICTURE']) && $params['PAGE'] != 'element') {
            $arrReturn[] = CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
        }
        if (!empty($arElement['DETAIL_PICTURE'])) {
            $arrReturn[] = CFile::ResizeImageGet($arElement['DETAIL_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
        }
        if ($params['MORE_PHOTO_CODE'] != '' && !empty($arElement['PROPERTIES'][$params['MORE_PHOTO_CODE']]['VALUE'])) {
            foreach ($arElement['PROPERTIES'][$params['MORE_PHOTO_CODE']]['VALUE'] as $picID) {
                if (intval($picID) > 0) {
                    $arrReturn[] = CFile::ResizeImageGet($picID, array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                }
            }
        }
        if ($params['NO_SKU'] == 'N' && is_array($arOffers) && count($arOffers) > 0) {
            foreach ($arOffers as $arOffer) {
                if (!empty($arOffer['PREVIEW_PICTURE']) && $arOffer['PREVIEW_PICTURE'] != $first_pic && $params['PAGE'] != 'element') {
                    $arrReturn[] = CFile::ResizeImageGet($arOffer['PREVIEW_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                }
                if (!empty($arOffer['DETAIL_PICTURE']) && $arOffer['DETAIL_PICTURE'] != $first_pic) {
                    $arrReturn[] = CFile::ResizeImageGet($arOffer['DETAIL_PICTURE'], array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                }
                if ($params['SKU_MORE_PHOTO_CODE'] != '' && !empty($arOffer['PROPERTIES'][$params['SKU_MORE_PHOTO_CODE']]['VALUE'])) {
                    foreach ($arOffer['PROPERTIES'][$params['SKU_MORE_PHOTO_CODE']]['VALUE'] as $picID) {
                        if ($picID != $first_pic && intval($picID) > 0) {
                            $arrReturn[] = CFile::ResizeImageGet($picID, array('width' => $arSizes['WIDTH'],'height' => $arSizes['HEIGHT']), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
                        }
                    }
                }
            }
        }
        if (is_array($arrReturn) && count($arrReturn) < 1) {
            $arrReturn[] = RSDevFunc::GetNoPhoto(array('MAX_WIDTH' => $arSizes['WIDTH'],'MAX_HEIGHT' => $arSizes['HEIGHT']));
        }
        return $arrReturn;
    }

    // add discount and print_discount to PRICES
    public static function AddPrintDiscount(array $arPrices): array
    {
        $arrReturn = array();

        if (is_array($arPrices) && count($arPrices) > 0) {
            foreach ($arPrices as $code => $arPrice) {
                $arrReturn[$code] = $arPrice;
                if ($arPrice['VALUE'] != $arPrice['DISCOUNT_VALUE']) {
                    $discount = $arPrice['VALUE'] - $arPrice['DISCOUNT_VALUE'];
                    $arrReturn[$code]['DISCOUNT'] = $discount;
                    $arrReturn[$code]['PRINT_DISCOUNT'] = FormatCurrency($discount, $arPrice['CURRENCY']);
                }
            }
        }

        return $arrReturn;
    }

    // get min price in offers
    public static function GetMinPrices(array $arOffers, string $considerCanBuy = 'N'): array
    {
        $arrReturn = array();
        if (is_array($arOffers) && count($arOffers) > 0) {
            $arrMinPrices = array();
            foreach ($arOffers[0]['PRICES'] as $priceCODE => $arPrice) {
                $arrMinPrices[$priceCODE] = array(
                    'OFFER_ID' => 0,
                    'KEY_IN_ARRAY' => 0,
                    'PRINT_PRICE' => '',
                    'PRICE' => 1000000000000000,
                    'PRINT_DISCOUNT_PRICE' => '',
                    'DISCOUNT_PRICE' => 1000000000000000,
                );
            }
            foreach ($arOffers as $key1 => $arOffer) {
                foreach ($arOffer['PRICES'] as $priceCODE => $arPrice) {
                    if (
                        ($arPrice['DISCOUNT_VALUE'] < $arrMinPrices[$priceCODE]['DISCOUNT_PRICE'] && $considerCanBuy == 'N') ||
                        ($arPrice['DISCOUNT_VALUE'] < $arrMinPrices[$priceCODE]['DISCOUNT_PRICE'] && $considerCanBuy == 'Y' && $arPrice['CAN_BUY'] == 'Y')
                    ) {
                        $arrMinPrices[$priceCODE] = array(
                            'OFFER_ID' => $arOffer['ID'],
                            'KEY_IN_ARRAY' => $key1,
                            'CAN_BUY' => $arPrice['CAN_BUY'],
                            'PRINT_PRICE' => $arPrice['PRINT_VALUE'],
                            'PRICE' => $arPrice['VALUE'],
                            'PRINT_DISCOUNT_PRICE' => $arPrice['PRINT_DISCOUNT_VALUE'],
                            'DISCOUNT_PRICE' => $arPrice['DISCOUNT_VALUE'],
                        );
                    }
                }
            }
            $arrReturn = $arrMinPrices;
        }
        return $arrReturn;
    }

    // price to property for sorting
    public static function OnAfterIBlockElementAddHandler(array $arFields): void
    {
        $ID = ( intval($arFields['ID']) > 0 ? $arFields['ID'] : $arFields['RESULT'] );
        $TMP_ID = (int) ($arFields['TMP_ID'] ?? 0);
        self::priceToProperty($ID, $TMP_ID);
    }

    public static function OnAfterIBlockElementUpdateHandler(array $arFields): void
    {
        $ID = ( intval($arFields['ID']) > 0 ? $arFields['ID'] : $arFields['RESULT'] );
        self::priceToProperty($ID);
    }

    public static function OnPriceUpdateAddHandler(int $ID, array $arFields): void
    {
        self::priceToProperty($arFields['PRODUCT_ID']);
    }

    public static function priceToProperty(int $ELEM_ID, int $TMP_ID = 0): void
    {
        if (
            !Loader::includeModule('iblock')
            || !Loader::includeModule('catalog')
        ) {
            return;
        }

        $propcode_cml2link = Option::get('redsign.devfunc', 'propcode_cml2link', 'CML2_LINK');
        $propcode_fakeprice = Option::get('redsign.devfunc', 'propcode_fakeprice', 'PROD_PRICE_FALSE');
        $price_for_fake = Option::get('redsign.devfunc', 'price_for_fake', '0');
        $ELEMENT_ID = intval($ELEM_ID);

        $res0 = \CIBlockElement::GetByID($ELEMENT_ID);
        $arElementFields = $res0->GetNext();

        if (!$arElementFields)
            return;

        $ELEMENT_IBLOCK_ID = intval($arElementFields['IBLOCK_ID']);

        // Check parameters
        if (
            $ELEMENT_ID
            && $ELEMENT_IBLOCK_ID
            && $propcode_cml2link != ''
            && $propcode_fakeprice != ''
            && $price_for_fake
        ) {
            // Get GROUPED ELEMENT ID
            $res1 = CIBlockElement::GetProperty($ELEMENT_IBLOCK_ID, $ELEMENT_ID, array('SORT' => 'ASC'), array('CODE' => $propcode_cml2link));
            if ($arFields1 = $res1->Fetch()) {
                // This is SKU
                $GROUP_ELEMENT_ID = intval($arFields1['VALUE']);
                if ($GROUP_ELEMENT_ID) {
                    // Get GROUPED IBLOCK_ID
                    $res2 = CIBlockElement::GetByID($GROUP_ELEMENT_ID);
                    if ($arFields2 = $res2->GetNext()) {
                        $GROUP_IBLOCK_ID = intval($arFields2['IBLOCK_ID']);
                        if ($GROUP_IBLOCK_ID) {
                            // Check property FAKE_PRICE isset
                            $FAKE_PRICE_PROP_ID = self::CheckIssetProperty($GROUP_IBLOCK_ID, $propcode_fakeprice);
                            if (intval($FAKE_PRICE_PROP_ID) > 0) {
                                // Get one offers with the lowest price
                                $res4 = CIBlockElement::GetList(
                                    array('CATALOG_PRICE_' . $price_for_fake => 'ASC'),
                                    array('IBLOCK_ID' => $ELEMENT_IBLOCK_ID, 'ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y', 'PROPERTY_' . $propcode_cml2link => $GROUP_ELEMENT_ID),
                                    false,
                                    array('nPageSize' => 1),
                                    array('ID', 'CATALOG_GROUP_' . $price_for_fake, 'PROPERTY_' . $propcode_cml2link)
                                );
                                if ($arFields4 = $res4->GetNext()) {
                                    if ((int)$arFields4['CATALOG_PRICE_' . $price_for_fake] > 0) {
                                        CIBlockElement::SetPropertyValues($GROUP_ELEMENT_ID, $GROUP_IBLOCK_ID, (float)$arFields4['CATALOG_PRICE_' . $price_for_fake], $FAKE_PRICE_PROP_ID);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                // This is simple (grouped) element
                $FAKE_PRICE_PROP_ID = self::CheckIssetProperty($ELEMENT_IBLOCK_ID, $propcode_fakeprice);
                if (intval($FAKE_PRICE_PROP_ID) > 0) {
                    $arFilter = array(
                        'LOGIC' => 'OR',
                        array( 'PROPERTY_' . $propcode_cml2link => $ELEMENT_ID ),
                        array( 'PROPERTY_' . $propcode_cml2link => '-' . $TMP_ID ),
                    );
                    $res2 = CIBlockElement::GetList(
                        array('CATALOG_PRICE_' . $price_for_fake => 'ASC'),
                        array('ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y', $arFilter),
                        false,
                        array('nPageSize' => 1),
                        array('ID', 'CATALOG_GROUP_' . $price_for_fake)
                    );
                    if ($arFields2 = $res2->GetNext()) {
                        // This element have SKU
                        if ((int)$arFields2['CATALOG_PRICE_' . $price_for_fake] > 0) {
                            CIBlockElement::SetPropertyValues($ELEMENT_ID, $ELEMENT_IBLOCK_ID, (float)$arFields2['CATALOG_PRICE_' . $price_for_fake], $FAKE_PRICE_PROP_ID);
                        }
                    } else {
                        // This element dont have SKU
                        $res4 = CIBlockElement::GetList(
                            array(),
                            array('IBLOCK_ID' => $ELEMENT_IBLOCK_ID, 'ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y', 'ID' => $ELEMENT_ID),
                            false,
                            array('nTopCount' => 1),
                            array('*', 'CATALOG_GROUP_' . $price_for_fake)
                        );
                        if ($arFields4 = $res4->GetNext()) {
                            if (intval($arFields4['CATALOG_PRICE_' . $price_for_fake]) > 0) {
                                CIBlockElement::SetPropertyValues($ELEMENT_ID, $ELEMENT_IBLOCK_ID, (float)$arFields4['CATALOG_PRICE_' . $price_for_fake], $FAKE_PRICE_PROP_ID);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function CheckIssetProperty(int $IBLOCK_ID, string $PROP_CODE = 'PROD_PRICE_FALSE'): int
    {
        $return = 0;

        if ($IBLOCK_ID > 0) {
            $res = CIBlockProperty::GetList([], ['ACTIVE' => 'Y', 'IBLOCK_ID' => $IBLOCK_ID, 'CODE' => $PROP_CODE]);
            if ($arProp = $res->GetNext()) {
                $return = $arProp['ID'];
            } else {
                $arFields = array(
                    'NAME' => Loc::getMessage('RSDEVFUNC_PRICE_FALSE_NAME'),
                    'ACTIVE' => 'Y',
                    'SORT' => '100000',
                    'CODE' => $PROP_CODE,
                    'PROPERTY_TYPE' => Iblock\PropertyTable::TYPE_NUMBER,
                    'IBLOCK_ID' => $IBLOCK_ID,
                    'WITH_DESCRIPTION' => 'N',
                );
                $iblockproperty = new \CIBlockProperty();
                $PropertyID = $iblockproperty->Add($arFields);
                $return = $PropertyID;
            }
        }

        return $return;
    }
}
