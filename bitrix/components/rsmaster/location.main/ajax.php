<?php

use \Bitrix\Main\Context;
use \Bitrix\Main\Localization\Loc;
use \Redsign\Master;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

if (!is_string($_REQUEST['siteId'])) {
    die();
}
if (preg_match('/^[a-z0-9_]{2}$/i', $_REQUEST['siteId']) === 1) {
    define('SITE_ID', $_REQUEST['siteId']);
}

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

Loc::loadMessages(__FILE__);

$arResult = array(
    'ERROR' => false,
    'SUCCESS' => false,
);

if (
    !\Bitrix\Main\Loader::includeModule('redsign.master') ||
    !\Bitrix\Main\Loader::includeModule('sale')
) {
    $arResult['ERROR'] = Loc::getMessage('RS_MODULE_NOT_INSTALLED');
}

$context = Context::getCurrent();
$request = $context->getRequest();

$action = trim($request->get('action'));
if (!$arResult['ERROR'] && check_bitrix_sessid()) {
    switch ($action) {
        case 'change':
            $locationId = $request->get('id');
            Master\Location::setMyCity($locationId);
            $arResult['SUCCESS'] = true;
            $arResult['id'] = $locationId;
            break;
        default:
            break;

    }
}

if (strtolower(SITE_CHARSET) != 'utf-8') {
    $arResult = $APPLICATION->ConvertCharsetArray($arResult, SITE_CHARSET, 'utf-8');
}

header('Content-Type: application/json');
echo json_encode($arResult);

$context->getResponse()->flush();
die();
