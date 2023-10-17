<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Web\Uri;
use Redsign\DevFunc\Sale\Location\Location;
use Redsign\DevFunc\Sale\Location\Region;

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

if (!is_string($_REQUEST['siteId'])) {
    die();
}
if (preg_match('/^[a-z0-9_]{2}$/i', $_REQUEST['siteId']) === 1) {
    define('SITE_ID', $_REQUEST['siteId']);
}

require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

Loc::loadMessages(__FILE__);

$arResult = array(
    'ERROR' => false,
    'SUCCESS' => false,
);

if (!Loader::includeModule('redsign.devfunc')) {
    $arResult['ERROR'] = Loc::getMessage('RS_MODULE_NOT_INSTALLED');
}

$context = \Bitrix\Main\Application::getInstance()->getContext();

/** @var \Bitrix\Main\HttpRequest */
$request = $context->getRequest();

$action = $request->get('action') ?: '';
if (empty($arResult['ERROR']) && check_bitrix_sessid()) {
    switch ($action) {
        case 'change':
            $locationId = (int) $request->get('id') ?: 0;

            if ($locationId == 0)
                break;

            $arResult['SUCCESS'] = true;
            $arResult['id'] = $locationId;

            $useRegions = Region::isUseRegionality();

            if ($useRegions) {
                $arRegions = Region::getRegions();
                $userRegion = [];

                if (is_array($arRegions) && count($arRegions) > 0) {
                    $server = $context->getServer();
                    foreach ($arRegions as $arRegion) {
                        if ($locationId == $arRegion['LOCATION_ID']) {
                            $userRegion = $arRegion;
                            break;
                        }
                    }
                    unset($arRegion);

                    if (empty($userRegion))
                        $userRegion = Region::getDefaultRegion();

                    if (!empty($userRegion)) {
                        $domainList = is_array($userRegion['LIST_DOMAINS']) ? $userRegion['LIST_DOMAINS'] : [];
                        if ($domainList && !in_array($server->getHttpHost(), $domainList)) {
                            /** @var string $backurl */
                            $backurl = $request->get('backurl') ?: '/';
                            $uri = new Uri($backurl);

                            $uri->setHost(reset($userRegion['LIST_DOMAINS']));
                            $uri->addParams([
                                // 'region' => $userRegion['ID'],
                                'location' => $locationId,
                            ]);

                            $redirect = $uri->getUri();

                            $arResult['redirect'] = $redirect;
                        } else {
                            Region::set($userRegion);
                            Location::setMyCity($locationId);
                        }
                    }
                }
            } else {
                Location::setMyCity($locationId);
            }

            break;

        default:
            break;
    }
}

if (strtolower(SITE_CHARSET) != 'utf-8') {
    $arResult = $APPLICATION->ConvertCharsetArray($arResult, SITE_CHARSET, 'utf-8');
}

header('Content-Type: application/json');
echo \Bitrix\Main\Web\Json::encode($arResult);

CMain::FinalActions();
die();
