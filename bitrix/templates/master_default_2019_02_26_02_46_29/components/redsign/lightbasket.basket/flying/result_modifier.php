<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

if (!Loader::includeModule('redsign.devfunc')) {
    return;
}

$arResult['RIGHT_WORD'] = RSDevFunc::BasketEndWord(count($arResult['ITEMS']));
