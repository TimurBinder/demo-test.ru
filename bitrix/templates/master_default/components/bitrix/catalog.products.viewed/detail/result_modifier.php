<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;
use \Redsign\Master\MyTemplate;

$component = $this->getComponent();

$arResult['MODULES']['redsign.master'] = Loader::includeModule('redsign.master');

// $arParams = $component->applyTemplateModifications();

$component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
$arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

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
