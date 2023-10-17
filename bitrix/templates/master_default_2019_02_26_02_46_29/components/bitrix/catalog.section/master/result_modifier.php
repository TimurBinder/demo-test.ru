<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;
use \Redsign\Master\MyTemplate;

$component = $this->getComponent();

$component->arParams['SHOW_SLIDER'] = 'N';

$arResult['MODULES']['redsign.master'] = Loader::includeModule('redsign.master');

if (empty($arParams['TEMPLATE_AJAXID']) || strlen($arParams['TEMPLATE_AJAXID']) < 1)
{
	 $component->arParams['TEMPLATE_AJAXID'] = CAjax::GetComponentID($component->componentName, $component->componentTemplate, $component->arParams['AJAX_OPTION_ADDITIONAL']);
}

if (empty($arParams['PRODUCT_BLOCKS']))
{
		$component->arParams['PRODUCT_BLOCKS'] = (is_string($arParams['PRODUCT_BLOCKS_ORDER']) && strlen($arParams['PRODUCT_BLOCKS_ORDER']) > 0)
			? explode(',', $arParams['PRODUCT_BLOCKS_ORDER'])
			: array();
}

if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
{
	foreach ($arResult['ITEMS'] as $iItemKey => $arItem)
	{
		$haveOffers = !empty($arItem['OFFERS']);
		
		if ($arParams['FILL_ITEM_ALL_PRICES'])
		{
			if ($haveOffers)
			{
				$bOfferCnt = 0;
				foreach ($arItem['OFFERS'] as $arOffer)
				{
					if (!is_array($arOffer['PRICES']) || count($arOffer['PRICES']) < 2)
					{
						$bOfferCnt++;
					}
				}
				if ($bOfferCnt == count($arOffer['PRICES']))
				{
					$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
				}        
				unset($arOffer, $bOfferCnt);

				// bitrix wtf
				if ($arResult['MODULES']['redsign.master'])
				{
					foreach ($arItem['OFFERS'] as $iOfferKey => $jsOffer)
					{
						IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$iItemKey]['OFFERS'][$iOfferKey]);
						$arResult['ITEMS'][$iItemKey]['JS_OFFERS'][$iOfferKey]['ITEM_ALL_PRICES'] = $arResult['ITEMS'][$iItemKey]['OFFERS'][$iOfferKey]['ITEM_ALL_PRICES'];
					}
				}
				
			}
			else
			{
				if (
					(!is_array($arItem['PRICES']) || count($arItem['PRICES']) < 2)
					&& (!is_array($arItem['ITEM_ALL_PRICES'][$arItem['ITEM_PRICE_SELECTED']]['PRICES']) || count($arItem['ITEM_ALL_PRICES'][$arItem['ITEM_PRICE_SELECTED']]['PRICES']) < 2)
				)
				{
					$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
				}
				
				// bitrix wtf
				if ($arResult['MODULES']['redsign.master'])
				{
					IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$iItemKey]);
				}
			}
		}
	}
}

$arParams = $component->applyTemplateModifications();

$component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
$arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

if (Loader::includeModule('redsign.devfunc'))
{
	\Redsign\DevFunc\Sale\Location\Region::editCatalogResult($arResult);
	if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
	{
		foreach ($arResult['ITEMS'] as $iItemKey => $arItem)
		{
			\Redsign\DevFunc\Sale\Location\Region::editCatalogItem($arResult['ITEMS'][$iItemKey]);
		}
		unset($iItemKey, $arItem);
	}
}

if (!empty($arResult['ITEMS']))
{
	$params = array(
	  'PROP_PRICE' => $arParams['PRICE_PROP'],
	  'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
	  'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
	  'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
	);

	foreach ($arResult['ITEMS'] as $key => $item)
	{
		if (!isset($arResult['CATALOGS'][$item['IBLOCK_ID']]) && $arResult['MODULES']['redsign.master'])
		{
			$arResult['ITEMS'][$key]['RS_PRICES'] = IblockElementExt::getPrice($item, $params);
		}
	}
	unset($key, $item, $params);
}

if ($arParams['SHOW_PARENT_DESCR'] == 'Y' && $arResult['ID'] == 0)
{
    $arOrder = array();
    $arFilter = array(
        'TYPE' => $arParams['IBLOCK_TYPE'],
        'ID' => $arParams['IBLOCK_ID'],
    );
    $bIncCnt = false;
    
    $dbIblock = CIBlock::getList($arOrder, $arFilter, $bIncCnt);
    
    if ($arIblock = $dbIblock->getNext())
	{
        $arResult['NAME'] = $arIblock['NAME'];
        $arResult['DESCRIPTION'] = $arIblock['DESCRIPTION'];
    }
    unset($arOrder, $arFilter, $bIncCnt);

}
