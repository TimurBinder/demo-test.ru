<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master\IblockElementExt;
use \Redsign\Master\MyTemplate;

$component = $this->getComponent();

$component->arParams['SHOW_SLIDER'] = 'N';
$component->arParams['PRODUCT_DISPLAY_MODE'] = 'N';

$arResult['MODULES']['redsign.master'] = Loader::includeModule('redsign.master');

$component->arParams['RS_SECTION_SHOW_BUTTON'] = isset($component->arParams['RS_SECTION_SHOW_BUTTON']) && $component->arParams['RS_SECTION_SHOW_BUTTON'] === 'Y' ? 'Y' : 'N';
$component->arParams['SHOW_ERROR_SECTION_EMPTY'] = isset($component->arParams['SHOW_ERROR_SECTION_EMPTY']) && $component->arParams['SHOW_ERROR_SECTION_EMPTY'] === 'Y' ? 'Y' : 'N';

if ($component->arParams['SHOW_ERROR_SECTION_EMPTY'] == 'Y')
{
	if (empty($component->arParams['MESS_ERROR_SECTION_EMPTY']))
	{
		$component->arParams['MESS_ERROR_SECTION_EMPTY'] = Loc::getMessage('RS_MM_BCS_MASTER_ERROR_EMPTY_ITEMS');
	}
}

if (
	empty($arParams['RS_SECTION_BUTTON_NAME']) || strlen($arParams['RS_SECTION_BUTTON_NAME']) < 1
	|| empty($arParams['RS_SECTION_BUTTON_LINK']) || strlen($arParams['RS_SECTION_BUTTON_LINK']) < 1
)
{
	 $component->arParams['RS_SECTION_SHOW_BUTTON'] = 'N';
}

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
	foreach ($arResult['ITEMS'] as $key => $item)
	{
		$haveOffers = !empty($item['OFFERS']);

		if ($arParams['FILL_ITEM_ALL_PRICES'])
		{
			if ($haveOffers)
			{
				$bOfferCnt = 0;
				foreach ($item['OFFERS'] as $arOffer)
				{
					if (!is_array($arOffer['PRICES']) || count($arOffer['PRICES']) < 2)
					{
						$bOfferCnt++;
					}
				}
				if (is_array($arOffer['PRICES']) && $bOfferCnt == count($arOffer['PRICES']))
				{
					$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
				}
				unset($arOffer, $bOfferCnt);

				// #bitrixwtf
				if ($arResult['MODULES']['redsign.master'])
				{
					foreach ($item['OFFERS'] as $offerKey => $offer)
					{
						IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$key]['OFFERS'][$offerKey]);
						$arResult['ITEMS'][$key]['JS_OFFERS'][$offerKey]['ITEM_ALL_PRICES'] = $arResult['ITEMS'][$key]['OFFERS'][$offerKey]['ITEM_ALL_PRICES'];
					}
					unset($offerKey, $offer);
				}
			}
			else
			{
				if (
					(!is_array($item['PRICES']) || count($item['PRICES']) < 2)
					&& (
						!is_array($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES'])
						|| count($item['ITEM_ALL_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICES']) < 2
					)
				) {
					$component->arParams['FILL_ITEM_ALL_PRICES'] = false;
				}

				// #bitrixwtf
				if ($arResult['MODULES']['redsign.master'])
				{
					IblockElementExt::fixCatalogItemFillAllPrices($arResult['ITEMS'][$key]);
				}
			}
		}
	}
	unset($key, $item);
}

$arParams = $component->applyTemplateModifications();

$component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
$arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

if (Loader::includeModule('redsign.devfunc'))
{
	\Redsign\DevFunc\Sale\Location\Region::editCatalogResult($arResult);
	if (is_array($arResult['ITEMS']) && count($arResult['ITEMS']) > 0)
	{
		foreach ($arResult['ITEMS'] as $key => $item)
		{
			\Redsign\DevFunc\Sale\Location\Region::editCatalogItem($arResult['ITEMS'][$key]);
		}
		unset($key, $item);
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
