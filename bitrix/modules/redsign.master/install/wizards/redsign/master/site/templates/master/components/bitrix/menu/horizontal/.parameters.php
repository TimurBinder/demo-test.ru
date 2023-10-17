<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Iblock;

if (!Loader::includeModule('iblock')) {
    return;
}

Loc::loadMessages(__FILE__);
