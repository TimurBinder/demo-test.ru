<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use \Bitrix\Main\Loader;

$arParams["FILTER_VIEW_MODE"] = (isset($arParams["FILTER_VIEW_MODE"]) && toUpper($arParams["FILTER_VIEW_MODE"]) == "HORIZONTAL") ? "HORIZONTAL" : "VERTICAL";
$arParams["POPUP_POSITION"] = (isset($arParams["POPUP_POSITION"]) && in_array($arParams["POPUP_POSITION"], array("left", "right"))) ? $arParams["POPUP_POSITION"] : "right";

/*
if (Loader::includeModule('redsign.tuning')) {
    $tuning = \Redsign\Tuning\TuningCore::getInstance();
    $instanceOptionManager = $tuning->getInstanceOptionMananger();

    $arParams['TEMPLATE_THEME'] = $instanceOptionManager->get('FILTER_THEME');
}
*/
if (!in_array($arParams['TEMPLATE_THEME'], array('default', 'lite'))) {
    $arParams['TEMPLATE_THEME'] = 'default';
}