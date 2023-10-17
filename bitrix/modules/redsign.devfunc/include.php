<?php

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'redsign.devfunc',
    [
        'RSDevFunc' => 'classes/general/main.php',
        'RSDevFuncOffersExtension' => 'classes/general/offersext.php',
        'RSDevFuncFilterExtension' => 'classes/general/filterext.php',
        'RSDevFuncResultModifier' => 'classes/general/result_modifier.php',
        'RSDevFuncParameters' => 'classes/general/parameters.php',
        'RSColor' => 'classes/general/color.php',
        'RSSeo' => 'classes/general/seo.php',
        // lib
        '\Redsign\DevFunc\Catalog\Product' => 'lib/catalog/product.php',
        '\Redsign\DevFunc\Iblock\Property' => 'lib/iblock/property.php',
        '\Redsign\DevFunc\Iblock\Template\Functions\Region' => 'lib/iblock/template/functions/region.php',
        '\Redsign\DevFunc\Sale\Location\Location' => 'lib/sale/location/location.php',
        '\Redsign\DevFunc\Sale\Location\Region' => 'lib/sale/location/region.php',
        '\Redsign\DevFunc\Module' => 'lib/module.php',
    ]
);

$pathJS = getLocalPath('js/redsign/devfunc');

$arJSCoreConfig = [
    'rs_core' => [
        'js' => $pathJS . '/core.js',
    ],
    'rs_color' => [
        'js' => $pathJS . '/color.js',
        'rel' => ['rs_core'],
    ],
];

foreach ($arJSCoreConfig as $ext => $arExt) {
    CJSCore::RegisterExt($ext, $arExt);
}

function RSDF_EasyAdd2Basket(int $productID, float $quantity, array $arParams): bool
{
    /** @var CMain $APPLICATION */
    global $APPLICATION;

    $return = false;
    $productID = IntVal($productID);

    if ($productID > 0 && \Bitrix\Main\Loader::includeModule('sale') && \Bitrix\Main\Loader::includeModule('catalog')) {
        $tmpQuantity = 0;
        $arProductProperties = [];
        $arRewriteFields = [];
        $intProductIBlockID = IntVal(CIBlockElement::GetIBlockByID($productID));
        $successfulAdd = true;

        if ($intProductIBlockID > 0) {
            // PROPERTIES
            if ($arParams['ADD_PROPERTIES_TO_BASKET'] == 'Y') {
                if ($intProductIBlockID == $arParams["IBLOCK_ID"]) {
                    if (!empty($arParams["PRODUCT_PROPERTIES"])) {
                        $arProductProperties = CIBlockPriceTools::CheckProductProperties(
                            $arParams["IBLOCK_ID"],
                            $productID,
                            $arParams["PRODUCT_PROPERTIES"],
                            $arParams["PRODUCT_PROPERTIES"],
                            $arParams['PARTIAL_PRODUCT_PROPERTIES'] == 'Y'
                        );

                        if (!is_array($arProductProperties)) {
                            $arProductProperties = [];
                        }
                    }
                } else {
                    //$skuAddProps = (isset($_REQUEST['basket_props']) && !empty($_REQUEST['basket_props']) ? $_REQUEST['basket_props'] : '');
                    if (!empty($arParams["OFFERS_CART_PROPERTIES"])) {
                        $arProductProperties = CIBlockPriceTools::GetOfferProperties(
                            $productID,
                            $arParams["IBLOCK_ID"],
                            $arParams["OFFERS_CART_PROPERTIES"]//,
                            //$skuAddProps
                        );
                    }
                }
            }

            // QUANTITY
            if ($arParams['USE_PRODUCT_QUANTITY']) {
                if ((float) $quantity > 0) {
                    $tmpQuantity = (float) $quantity;
                }
            }
            if (0 >= $tmpQuantity) {
                $rsRatios = CCatalogMeasureRatio::getList(
                    [],
                    ['PRODUCT_ID' => $productID],
                    false,
                    false,
                    ['PRODUCT_ID', 'RATIO']
                );
                if ($arRatio = $rsRatios->Fetch()) {
                    $intRatio = IntVal($arRatio['RATIO']);
                    $dblRatio = doubleval($arRatio['RATIO']);
                    $tmpQuantity = ($dblRatio > $intRatio ? $dblRatio : $intRatio);
                }
            }
            if (0 >= $tmpQuantity)
                $tmpQuantity = 1;
        } else {
            $strError = 'CATALOG_ELEMENT_NOT_FOUND';
            $successfulAdd = false;
        }

        if ($successfulAdd) {
            if (!Add2BasketByProductID($productID, $tmpQuantity, $arRewriteFields, $arProductProperties)) {
                if ($ex = $APPLICATION->GetException()) {
                    $strError = $ex->GetString();
                } else {
                    $strError = 'CATALOG_ERROR2BASKET';
                }
                $successfulAdd = false;
            } else {
                $return = true;
            }
        }
    }

    return $return;
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'compatibility.php';
