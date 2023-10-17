<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}


if (empty($arParams['PATH_TO_CART']) && isset($arParams['PATH_TO_BASKET'])) {
    $arParams['PATH_TO_CART'] = $arParams['PATH_TO_BASKET'];
}