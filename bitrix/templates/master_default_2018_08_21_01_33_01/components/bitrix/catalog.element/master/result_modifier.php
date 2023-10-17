<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;

$component = $this->getComponent();

$component->arParams['SHOW_SLIDER'] = 'N';

$arParams = $component->applyTemplateModifications();

$arParams['ADD_TO_BASKET_ACTION'] = $arResult['ORIGINAL_PARAMETERS']['ADD_TO_BASKET_ACTION'];

if (Loader::includeModule('redsign.master')) {

    $params = array(
      'PROP_PRICE' => $arParams['PRICE_PROP'],
      'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
      'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
      'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
    );

    $arItems = array(0 => &$arResult);
    IblockElementExt::addPrices($arItems, $params);
}


if ($arParams['ARTNUMBER_PROP'] != '' && $arParams['ARTNUMBER_PROP'] != '-') {
	$arParams['ARTNUMBER_PROP'] = array($arParams['IBLOCK_ID'] => $arParams['ARTNUMBER_PROP']);
} else {
    $arParams['ARTNUMBER_PROP'] = '';
}

$arParams['DISPLAY_PROPERTIES_MAX'] = (intval($arParams['DISPLAY_PROPERTIES_MAX']) > 0 ? intval($arParams['DISPLAY_PROPERTIES_MAX']) : false);
$arParams['OFFERS_PROPERTIES_MAX'] = (intval($arParams['OFFERS_PROPERTIES_MAX']) > 0 ? intval($arParams['OFFERS_PROPERTIES_MAX']) : false);

if (is_array($arParams['TABS']) && count($arParams['TABS']) > 0) {

    $arResult['TABS'] = $arParams['TABS'];

    if (empty($arParams['TABS_ORDER'])) {
        $arParams['TABS_ORDER'] = 'detail,props,comments';
    }

    if (is_string($arParams['TABS_ORDER'])) {
        $arParams['TABS_ORDER'] = explode(',', $arParams['TABS_ORDER']);
    }
}

if (is_array($arParams['BLOCK_LINES']) && count($arParams['BLOCK_LINES']) > 0) {

    $arResult['BLOCK_LINES'] = $arParams['BLOCK_LINES'];

    if (empty($arParams['BLOCK_LINES_ORDER'])) {
        $arParams['BLOCK_LINES_ORDER'] = '';
    }

    
    if (is_string($arParams['BLOCK_LINES_ORDER'])) {
        $arParams['BLOCK_LINES_ORDER'] = explode(',', $arParams['BLOCK_LINES_ORDER']);
    }
}

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


foreach ($arParams['TAB_PROPERTIES'] as $iPropKey => $sPropCode)
{
	if ('' != $sPropCode && 'F' == $arResult['PROPERTIES'][$sPropCode]['PROPERTY_TYPE'])
	{
		if (is_array($arResult['PROPERTIES'][$sPropCode]['VALUE']))
		{
			foreach ($arResult['PROPERTIES'][$sPropCode]['VALUE'] as $iPropValKey => $iPropVal)
			{
				$rsFile = CFile::GetByID($iPropVal);
				if ($arFile = $rsFile->Fetch())
				{
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
