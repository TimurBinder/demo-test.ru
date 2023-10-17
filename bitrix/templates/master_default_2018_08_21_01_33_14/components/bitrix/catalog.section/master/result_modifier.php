<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;

$component = $this->getComponent();

$component->arParams['SHOW_SLIDER'] = 'N';

$arParams = $component->applyTemplateModifications();

if (Loader::includeModule('redsign.master')) {

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
