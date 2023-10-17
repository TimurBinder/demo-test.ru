<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

use \Bitrix\Main\Loader;
use \Redsign\Master\IblockElementExt;

$arParams['DETAIL_TEMPLATE'] = !empty($arParams['DETAIL_TEMPLATE']) ?  $arParams['DETAIL_TEMPLATE'] : 'master';
$arParams['LIST_TEMPLATE'] = !empty($arParams['LIST_TEMPLATE']) ?  $arParams['LIST_TEMPLATE'] : 'master';
$arParams['USE_SORTER'] = $arParams['USE_SORTER'] === 'N' ?  $arParams['USE_SORTER'] : 'Y';