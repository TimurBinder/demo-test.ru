<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

use \Bitrix\Main\Loader;

$arParams['MODE'] = 'line';

if ($arParams['USE_OWL'] == 'Y') {
    $arParams['OWL_PARAMS'] = array(
        'items' => 1,
        'dots' => true,
        'nav' => false
    );

    $arParams['MODE'] = 'carousel';
    $arParams['SHOW_DESCRIPTION'] = $arParams['SHOW_FEEDBACK_BUTTON'] = 'N';
}
