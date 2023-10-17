<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) {
	die();
}

use \Bitrix\Main\Page\Asset;
use \Redsign\Master\SVGIconsManager;

$asset = Asset::getInstance();
$asset->addJs(SITE_TEMPLATE_PATH.'/assets/vendor/bodymovin/bodymovin.js');

if (is_array($arResult['SVG_ICONS']) && count($arResult['SVG_ICONS']) > 0) {
    
    foreach ($arResult['SVG_ICONS'] as $sIconName) {
        SVGIconsManager::pushIcon($sIconName);
    }

}
