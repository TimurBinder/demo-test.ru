<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

if (!Loader::includeModule('iblock')) {
    return;
}

if ($arParams['USE_OWL'] == 'Y') {
    $arParams['OWL_PARAMS'] = array(
        'items' => 4,
        'responsive' => array(
            '0' => array('items' => '1'),
            '480' => array('items' => $arParams['OWL_PHONE']),
            '769' => array('items' => $arParams['OWL_TABLET']),
            '996' => array('items' => $arParams['OWL_PC']),
        ),
        'autoplay' => $arParams['OWL_AUTOPLAY'] == 'Y',
        'autoplaySpeed' => $arParams['OWL_CHANGE_SPEED'],
        'smartSpeed' => $arParams['OWL_CHANGE_SPEED'],
        'autoplayTimeout' => $arParams['OWL_CHANGE_DELAY'],
        'dots' => false,
        'nav' => true,
        'margin' => 20,
        'navText' => array(
          '<svg class="icon-svg"><use xlink:href="#svg-arrow-thin-left"></use></svg>',
          '<svg class="icon-svg"><use xlink:href="#svg-arrow-thin-right"></use></svg>'
        )
    );
}

if (isset($arParams['RS_LINK_PROP']) && $arParams['RS_LINK_PROP'] != '-') {
    foreach ($arResult['ITEMS'] as &$arItem) {
        if (isset($arItem['DISPLAY_PROPERTIES'][$arParams['RS_LINK_PROP']])) {
            $arItem['DETAIL_PAGE_URL'] = $arItem['DISPLAY_PROPERTIES'][$arParams['RS_LINK_PROP']]['VALUE'];
        }
    }
    unset($arItem);
}


//DATE FORMAT
$monthes = array(1 => Loc::getMessage('RS.MONTH_JANUARY'), Loc::getMessage('RS.MONTH_FEBRUARY'), Loc::getMessage('RS.MONTH_MARCH'),
   Loc::getMessage('RS.MONTH_APRIL'), Loc::getMessage('RS.MONTH_MAY'), Loc::getMessage('RS.MONTH_JUNE'),
   Loc::getMessage('RS.MONTH_JULY'), Loc::getMessage('RS.MONTH_AUGUST'), Loc::getMessage('RS.MONTH_SEPTEMBER'),
   Loc::getMessage('RS.MONTH_OCTOBER'), Loc::getMessage('RS.MONTH_NOVEMBER'), Loc::getMessage('RS.MONTH_DECEMBER')
);

foreach ($arResult['ITEMS'] as $key => $arItem) {
  if(!is_null($arItem["ACTIVE_FROM"])) {
    $date = strtotime($arItem["ACTIVE_FROM"]);
    $month = date("n", $date);
    $arResult['ITEMS'][$key]["DISPLAY_ACTIVE_FROM_FORMATED"] = date("d", $date).' '.$monthes[$month].' '.date("Y", $date);
  }
}
