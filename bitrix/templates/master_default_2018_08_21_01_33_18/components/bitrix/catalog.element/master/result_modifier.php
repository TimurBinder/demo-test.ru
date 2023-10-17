<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;
use \Redsign\Master\MyTemplate;

$component = $this->getComponent();

$arResult['MODULES']['redsign.master'] = Loader::includeModule('redsign.master');

if (!is_array($arResult['CATALOG']) && $arResult['MODULES']['redsign.master']) {

    $params = array(
      'PROP_PRICE' => $arParams['PRICE_PROP'],
      'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
      'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
      'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
    );

    $arItems = array(0 => &$arResult);
    IblockElementExt::addPrices($arItems, $params);
    
    $component->arParams['IS_USE_CART'] = isset($arParams['IS_USE_CART']) && $arParams['IS_USE_CART'] === 'Y' ? 'Y' : 'N';

}
else
{
    $component->arParams['IS_USE_CART'] = 'N';
    
    if ($arResult['CATALOG']['CATALOG_TYPE'] == \CCatalogSku::TYPE_FULL) {
        $arResult['OFFERS_IBLOCK_ID'] = $arResult['CATALOG']['IBLOCK_ID'];
    }
}


$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
    if (isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])) {
        $actualItem = &$arResult['OFFERS'][$arResult['OFFERS_SELECTED']];
    } else {
        $actualItem = &$arResult['OFFERS'][0];
    }
}
else
{
	$actualItem = &$arResult;
}

if ($arParams['ARTNUMBER_PROP'] != '' && $arParams['ARTNUMBER_PROP'] != '-') {
	$component->arParams['ARTNUMBER_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ARTNUMBER_PROP']);
} else {
    $component->arParams['ARTNUMBER_PROP'] = array();
}

if ($arParams['BRAND_PROP'] != '' && $arParams['BRAND_PROP'] != '-') {
    $component->arParams['BRAND_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['BRAND_PROP']);
} else {
    $component->arParams['BRAND_PROP'] = array();
}

if ($arResult['OFFERS_IBLOCK_ID']) {
    if ($arParams['OFFER_ARTNUMBER_PROP'] != '' && $arParams['OFFER_ARTNUMBER_PROP'] != '-') {
        $component->arParams['ARTNUMBER_PROP'][$arResult['OFFERS_IBLOCK_ID']] = $arParams['OFFER_ARTNUMBER_PROP'];
    }
}

$component->arParams['DISPLAY_PROPERTIES_MAX'] = (intval($arParams['DISPLAY_PROPERTIES_MAX']) > 0 ? intval($arParams['DISPLAY_PROPERTIES_MAX']) : false);
$component->arParams['OFFERS_PROPERTIES_MAX'] = (intval($arParams['OFFERS_PROPERTIES_MAX']) > 0 ? intval($arParams['OFFERS_PROPERTIES_MAX']) : false);

if ($arParams['FILL_ITEM_ALL_PRICES']) {
    if ($haveOffers) {
        
        $bOfferCnt = 0;
        foreach ($arResult['OFFERS'] as $arOffer) {
            if (!is_array($arOffer['PRICES']) || count($arOffer['PRICES']) < 2) {
                $bOfferCnt++;
            }
        }
        if ($bOfferCnt == count($arOffer['PRICES'])) {
            $component->arParams['FILL_ITEM_ALL_PRICES'] = false;
        }        
        unset($arOffer, $bOfferCnt);

    } else {
        if (!is_array($arResult['PRICES']) || count($arResult['PRICES']) < 2) {
            $component->arParams['FILL_ITEM_ALL_PRICES'] = false;
        }
    }
}

$component->arParams['SHOW_SLIDER'] = 'N';

$component->arParams['ADD_TO_BASKET_ACTION'] = $arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'];

if (empty($arParams['PRODUCT_INFO_BLOCK']) && strlen($arParams['PRODUCT_INFO_BLOCK_ORDER']) > 0) {
    if (is_string($arParams['PRODUCT_INFO_BLOCK_ORDER'])) {
        $component->arParams['PRODUCT_INFO_BLOCK'] = explode(',', $arParams['PRODUCT_INFO_BLOCK_ORDER']);
    }
}

if (is_array($arParams['TABS']) && count($arParams['TABS']) > 0) {

    $arResult['TABS'] = $arParams['TABS'];

    if (empty($arParams['TABS_ORDER'])) {
        $component->arParams['TABS_ORDER'] = 'detail,props,comments';
    }

    if (is_string($arParams['TABS_ORDER'])) {
        $component->arParams['TABS_ORDER'] = explode(',', $arParams['TABS_ORDER']);
    }
}

if (is_array($arParams['BLOCK_LINES']) && count($arParams['BLOCK_LINES']) > 0) {

    $arResult['BLOCK_LINES'] = $arParams['BLOCK_LINES'];

    if (empty($arParams['BLOCK_LINES_ORDER'])) {
        $component->arParams['BLOCK_LINES_ORDER'] = '';
    }
    
    if (is_string($arParams['BLOCK_LINES_ORDER'])) {
        $component->arParams['BLOCK_LINES_ORDER'] = explode(',', $arParams['BLOCK_LINES_ORDER']);
    }
}

$arParams = $component->applyTemplateModifications();

$component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
$arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

if ($arResult['DETAIL_TEXT'] != '') {
    if ($arResult['DETAIL_TEXT_TYPE'] === 'html') {
        if (preg_match_all('#<table.*?>.*</table>#is', $arResult['DETAIL_TEXT'], $arMatches)) {
            $arResult['DETAIL_TEXT'] = preg_replace('#<table.*?>.*</table>#is', '<div class="table-responsive">$0</div>', $arResult['DETAIL_TEXT']);
        }
        if (preg_match_all('#<iframe.*?>.*</iframe>#is', $arResult['DETAIL_TEXT'], $arMatches)) {
            $arResult['DETAIL_TEXT'] = preg_replace('#<iframe.*?>.*</iframe>#is', '<div class="table-responsive">$0</div>', $arResult['DETAIL_TEXT']);
        }
    }
}

foreach (array_merge($arParams['TABS'], $arParams['BLOCK_LINES']) as $blockName) {
    if (substr($blockName, 0, 5) == 'prop_') {
        $sPropCode = substr($blockName, 5);

        if (!empty($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {

            if ($arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'] == 'S' && isset($arResult['DISPLAY_PROPERTIES'][$sPropCode])) {
                if (preg_match_all('#<table.*?>.*</table>#is', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'], $arMatches)) {
                    $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'] = preg_replace('#<table.*?>.*</table>#is', '<div class="table-responsive">$0</div>', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
                }
                
                if (preg_match_all('#<iframe.*?>.*</iframe>#is', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'], $arMatches)) {
                    $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE'] = preg_replace('#<iframe.*?>.*</iframe>#is', '<div class="table-responsive">$0</div>', $arResult['DISPLAY_PROPERTIES'][$sPropCode]['DISPLAY_VALUE']);
                }
            }
        }
    }
}

foreach ($arParams['TAB_PROPERTIES'] as $iPropKey => $sPropCode) {
	
    if ('' != $sPropCode && 'F' == $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE']) {
        
		if (is_array($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
            
			foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropValKey => $iPropVal) {
                
				$rsFile = CFile::GetByID($iPropVal);
				if ($arFile = $rsFile->Fetch()) {
					$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey] = $arFile;
					$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FULL_PATH'] = '/upload/'.$arFile['SUBDIR'].'/'.$arFile['FILE_NAME'];
					$tmp = explode('.', $arFile['FILE_NAME']);
					$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_EXT'] = end($tmp);
					switch($arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_EXT'])
					{
						case 'docx':
						case 'doc':
							$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_ICON'] = 'doc';
							break;
						case 'xls':
						case 'xlsx':
							$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_ICON'] = 'xls';
							break;
						case 'pdf':
							$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_ICON'] = 'pdf';
							break;
						default:
							$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['FILE_ICON'] = 'txt';
							break;
					}
					$arResult['PROPERTIES'][$sPropCode]['VALUE'][$iPropValKey]['SIZE'] = CFile::FormatSize($arFile['FILE_SIZE'], 1);
				}
			}
		}
	}
}

// product deals
$arResult['PRODUCT_DEALS'] = array();
if ($arResult['SECTION']['ID'] > 0 && strlen($arParams['PRODUCT_DEALS_USER_FIELDS']) > 0) {
    $sectionIterator = \CIBlockSection::GetList(
        array(),
        array(
            'IBLOCK_ID' => $arResult['SECTION']['IBLOCK_ID'],
            'ID' => $arResult['SECTION']['ID'],
        ),
        false,
        array(
            'ID',
            $arParams['PRODUCT_DEALS_USER_FIELDS'],
        )
    );
    
    if ($arSection = $sectionIterator->GetNext()) {
        if (is_array($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']]) && count($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']]) > 0) {
            foreach ($arSection[$arParams['PRODUCT_DEALS_USER_FIELDS']] as $arValue) {
                $arResult['PRODUCT_DEALS'][$arValue] = array();
            }
            unset($arValue);
        }
    }
    unset($sectionIterator, $arSection);
}

if ($arParams['PRODUCT_DEALS_PROP'] != '' && $arParams['PRODUCT_DEALS_PROP'] != '-') {
    if (is_array($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE']) && count($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE']) > 0) {
        foreach ($arResult['PROPERTIES'][$arParams['PRODUCT_DEALS_PROP']]['VALUE'] as $arValue) {
            $arResult['PRODUCT_DEALS'][$arValue] = array();
        }
        unset($arValue);
    }
}

if (is_array($arResult['PRODUCT_DEALS']) && count($arResult['PRODUCT_DEALS']) > 0) {
    
    $elementIterator = \CIBlockElement::GetList(
        array(),
        array(
            // 'IBLOCK_ID' => $this->arParams['LINK_IBLOCK_ID'],
            'IBLOCK_ACTIVE' => 'Y',
            'ACTIVE_DATE' => 'Y',
            'ACTIVE' => 'Y',
            'CHECK_PERMISSIONS' => 'Y',
            // 'IBLOCK_TYPE' => $this->arParams['LINK_IBLOCK_TYPE'],
            '=ID' => array_keys($arResult['PRODUCT_DEALS']),
        ),
        false,
        false,
        array(
            'ID',
            'IBLOCK_ID',
            'NAME',
            'PREVIEW_TEXT',
            'DETAIL_PAGE_URL',
        )
    );
    
    while ($arElement = $elementIterator->GetNext()) {
        if (isset($arResult['PRODUCT_DEALS'][$arElement['ID']])) {
            $arResult['PRODUCT_DEALS'][$arElement['ID']] = $arElement;
        }
    }
    unset($elementIterator, $arElement);
    
}

// product images resize
$arProductImages = array();
if (!empty($actualItem['DETAIL_PICTURE'])) {
    $arProductImages[] = &$actualItem['DETAIL_PICTURE'];
}

if (is_array($actualItem['MORE_PHOTO']) && count($actualItem['MORE_PHOTO']) > 0) {
    
    foreach ($actualItem['MORE_PHOTO'] as $key => $arPhoto) {
        $arProductImages[] = &$actualItem['MORE_PHOTO'][$key];
    }
}

if ($haveOffers) {
    foreach ($arResult['JS_OFFERS'] as $ind => $jsOffer) {
        
        if (!empty($arResult['OFFERS'][$ind]['PREVIEW_PICTURE'])) {
            $arResult['JS_OFFERS'][$ind]['PREVIEW_PICTURE'] = array(
                'ID' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['ID'],
                'SRC' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['SRC'],
                'WIDTH' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['WIDTH'],
                'HEIGHT' => $arResult['OFFERS'][$ind]['PREVIEW_PICTURE']['HEIGHT'],
            );
        }
        
        if (!empty($arResult['OFFERS'][$ind]['DETAIL_PICTURE'])) {
            $arResult['JS_OFFERS'][$ind]['DETAIL_PICTURE'] = array(
                'ID' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['ID'],
                'SRC' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['SRC'],
                'WIDTH' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['WIDTH'],
                'HEIGHT' => $arResult['OFFERS'][$ind]['DETAIL_PICTURE']['HEIGHT'],
            );
        }
        
        if (!empty($jsOffer['DETAIL_PICTURE'])) {
            $arProductImages[] = &$arResult['JS_OFFERS'][$ind]['DETAIL_PICTURE'];
        }
        
        if (is_array($jsOffer['SLIDER']) && count($jsOffer['SLIDER']) > 0) {
            foreach ($jsOffer['SLIDER'] as $iSlideKey => $arSlide) {
                $arProductImages[] = &$arResult['JS_OFFERS'][$ind]['SLIDER'][$iSlideKey];
            }
        }
        
        // bitrix wtf
        if ($arParams['FILL_ITEM_ALL_PRICES']) {
            if (is_array($arResult['OFFERS'][$ind]['ITEM_ALL_PRICES']) && count($arResult['OFFERS'][$ind]['ITEM_ALL_PRICES']) > 0) {
                foreach ($arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'] as $iRangeKey => $arRange) {
                    if (is_array($arRange['PRICES']) && count(is_array($arRange['PRICES'])) > 0) {
                        foreach ($arRange['PRICES'] as $iPriceKey => $arPrice) {
                            $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_PRICE'] = \CCurrencyLang::CurrencyFormat(
                                $arPrice['RATIO_PRICE'],
                                $arPrice['CURRENCY'],
                                true
                            );
                            $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_BASE_PRICE'] = \CCurrencyLang::CurrencyFormat(
                                $arPrice['RATIO_BASE_PRICE'],
                                $arPrice['CURRENCY'],
                                true
                            );
                            $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PRINT_RATIO_DISCOUNT'] = \CCurrencyLang::CurrencyFormat(
                                $arPrice['RATIO_DISCOUNT'],
                                $arPrice['CURRENCY'],
                                true
                            );
                            $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'][$iRangeKey]['PRICES'][$iPriceKey]['PERCENT'] = roundEx(100*$arPrice['DISCOUNT']/$arPrice['BASE_PRICE'], 0);
                        }
                        unset($iPriceKey, $arPrice);
                    }
                }
                unset($iRangeKey, $arRange);
            }
            $arResult['JS_OFFERS'][$ind]['ITEM_ALL_PRICES'] = $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'];
        }
        
        if ($arParams['FILL_ITEM_ALL_PRICES']) {
            $arResult['JS_OFFERS'][$ind]['ITEM_ALL_PRICES'] = $arResult['OFFERS'][$ind]['ITEM_ALL_PRICES'];
        }
    }
}

if (is_array($arProductImages) && count($arProductImages) > 0) {
    
    foreach ($arProductImages as $key => $arPhoto)
    {
        if ($arPhoto['ID'] > 0) {
            $arProductImages[$key]['RESIZE'] = CFile::ResizeImageGet(
                $arPhoto['ID'],
                array('width' => 450, 'height' => 450),
                BX_RESIZE_IMAGE_PROPORTIONAL,
                true
            );
        }
    }
    unset($key, $arPhoto);
}

// product brands

if ($arParams['BRAND_USE'] == 'Y') {

    $arResult['BRANDS'] = array();

    $sBrandPropCode = $arParams['BRAND_PROP'][$arResult['IBLOCK_ID']];

    if ($sBrandPropCode != '' && isset($arResult['PROPERTIES'][$sBrandPropCode])) {
        if (is_array($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'])) {
            foreach ($arResult['PROPERTIES'][$sBrandPropCode]['VALUE'] as $iPropValue => $sPropValue) {
                $arResult['BRANDS'][$sPropValue] = array();
            }
        } else {
            $arResult['BRANDS'][$arResult['PROPERTIES'][$sBrandPropCode]['VALUE']] = array();
        }
    }

    if (
        intval($arParams['BRAND_IBLOCK_ID']) > 0 && strlen($arParams['BRAND_IBLOCK_BRAND_PROP']) > 0 &&
        is_array($arResult['BRANDS']) && count($arResult['BRANDS']) > 0
    ) {
        $dbBrands = CIBlockElement::GetList(
            array(),
            $arFilter = array(
                'IBLOCK_ID' => $arParams['BRAND_IBLOCK_ID'],
                'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'] => array_keys($arResult['BRANDS']),
            ),
            false,
            false,
            array(
                'ID',
                'IBLOCK_ID',
                'NAME',
                'PREVIEW_PICTURE',
                'DETAIL_PAGE_URL',
                'PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'],
            )
        );
        while ($arBrand = $dbBrands->GetNext()) {
            if ($arBrand['PREVIEW_PICTURE']) {
                $arBrand['PREVIEW_PICTURE'] = CFile::GetFileArray($arBrand['PREVIEW_PICTURE']);
                $arBrand['PREVIEW_PICTURE']['RESIZE'] = CFile::ResizeImageGet(
                    $arBrand['PREVIEW_PICTURE'],
                    array('width' => 120, 'height' => 120),
                    BX_RESIZE_IMAGE_PROPORTIONAL,
                    true
                );
            }
            $arResult['BRANDS'][$arBrand['PROPERTY_'.$arParams['BRAND_IBLOCK_BRAND_PROP'].'_VALUE']] = $arBrand;
        }

    } else {

        $arFilterProps = array(
            $arParams['BRAND_PROP'],
        );

        foreach ($arFilterProps as $arProps) {
            $sPropCode = $arProps[$arResult['IBLOCK_ID']];

            if (isset($arResult['PROPERTIES'][$sPropCode])) {
                if (is_array($arResult['PROPERTIES'][$sPropCode]['VALUE'])) {
                    foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropValue => $sPropValue) {
                        $arResult['PROPERTIES'][$sPropCode]['FILTER_URL'][] = $arResult['LIST_PAGE_URL']
                            .(strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['FILTER_NAME'].'_'
                            .$arResult['PROPERTIES'][$sPropCode]['ID'].'_'
                            .abs(crc32($arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
                                ? $arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID'][$iPropValue]
                                : htmlspecialcharsbx($sPropValue))
                            ).'=Y&set_filter=Y';
                    }
                } else {
                    $arResult['PROPERTIES'][$sPropCode]['FILTER_URL'] = $arResult['LIST_PAGE_URL']
                        .(strpos($arResult['LIST_PAGE_URL'], '?') === false ? '?' : '').$arParams['FILTER_NAME'].'_'
                        .$arResult['PROPERTIES'][$sPropCode]['ID'].'_'
                        .abs(crc32($arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
                            ? $arResult['PROPERTIES'][$sPropCode]['VALUE_ENUM_ID']
                            : htmlspecialcharsbx($arResult['PROPERTIES'][$sPropCode]['VALUE']))
                        ).'=Y&set_filter=Y';
                }
            }
        }
    }
}
