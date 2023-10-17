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

if (empty($arParams['PRODUCT_BLOCKS']) && strlen($arParams['PRODUCT_BLOCKS_ORDER']) > 0) {
    if (is_string($arParams['PRODUCT_BLOCKS_ORDER'])) {
        $component->arParams['PRODUCT_BLOCKS'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
    }
}

$arParams = $component->applyTemplateModifications();

$component->arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];
$arParams['ADD_TO_BASKET_ACTION'] = $arParams['~ADD_TO_BASKET_ACTION'];

$arResult['MODULES']['redsign.master'] = Loader::includeModule('redsign.master');

if (!is_array($arResult['CATALOG']) && $arResult['MODULES']['redsign.master']) {

    $params = array(
      'PROP_PRICE' => $arParams['PRICE_PROP'],
      'PROP_DISCOUNT' => $arParams['DISCOUNT_PROP'],
      'PROP_CURRENCY' => $arParams['CURRENCY_PROP'],
      'PRICE_DECIMALS' => $arParams['PRICE_DECIMALS'],
    );

    IblockElementExt::addPrices($arResult['ITEMS'], $params);
}

if ($arParams['SHOW_PARENT_DESCR'] == 'Y' && $arResult['ID'] == 0) {

    $arOrder = array();
    $arFilter = array(
        'TYPE' => $arParams['IBLOCK_TYPE'],
        'ID' => $arParams['IBLOCK_ID'],
    );
    $bIncCnt = false;
    
    $dbIblock = CIBlock::getList($arOrder, $arFilter, $bIncCnt);
    
    if ($arIblock = $dbIblock->getNext()) {
        $arResult['NAME'] = $arIblock['NAME'];
        $arResult['DESCRIPTION'] = $arIblock['DESCRIPTION'];
    }
    unset($arOrder, $arFilter, $bIncCnt);

}
