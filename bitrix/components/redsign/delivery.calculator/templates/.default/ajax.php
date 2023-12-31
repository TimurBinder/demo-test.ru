<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Loader;
use Bitrix\Sale\Location;

define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NO_AGENT_STATISTIC', 'Y');
define('DisableEventsCheck', true);
define('BX_SECURITY_SHOW_MESSAGE', true);
define('NOT_CHECK_PERMISSIONS', true);


$siteId = isset($_REQUEST['SITE_ID']) && is_string($_REQUEST['SITE_ID']) ? $_REQUEST['SITE_ID'] : '';
$siteId = substr(preg_replace('/[^a-z0-9_]/i', '', $siteId), 0, 2);
if (!empty($siteId) && is_string($siteId)) {
    define('SITE_ID', $siteId);
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');


/** @var \Bitrix\Main\HttpRequest */
$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter());

if (!Loader::includeModule('sale')) {
    return;
}

$params = $request->getPost('arParams');
$templateName = $request->getPost('templateName');

$params['ELEMENT_ID'] = (int) $params['ELEMENT_ID'];
$params['AJAX_CALL'] = 'Y';

if (empty($params['LOCATION_FROM'])) {
    $params['LOCATION_FROM'] = Option::get('sale', 'location');
}

if (empty($params['LOCATION_TO']) && Loader::includeModule('redsign.location')) {
    if (isset($_SESSION['RSLOCATION']['LOCATION'])) {
        $locationId = $_SESSION['RSLOCATION']['LOCATION']['ID'];
        if ($location = Location\LocationTable::getRowById($locationId)) {
            $params['LOCATION_TO'] = $location['CODE'];
        }
    } else {
        $detectLocation = CRS_Location::GetCityName();
        $cityName = $detectLocation['CITY_NAME'];
        $locationIterator = Location\LocationTable::getList(array(
            'filter' => array(
                '=NAME.NAME' => $cityName
            ),
            'select' => array(
                'ID',
                'CODE'
            )
        ));

        if ($locationIterator->fetch()) {
            $params['LOCATION_TO'] = $location['CODE'];
        }
    }
}

if (empty($params['LOCATION_ZIP']) && strlen($params['LOCATION_TO']) > 0) {
    $locationIterator = Location\ExternalTable::getList(array(
        'filter' => array(
            '=SERVICE.CODE' => \CSaleLocation::ZIP_EXT_SERVICE_CODE,
            '=LOCATION.CODE' => $params['LOCATION_TO'],
        ),
        'select' => array('ID', 'XML_ID'),
    ));

    if ($location = $locationIterator->fetch()) {
        $params['LOCATION_ZIP'] = $location['XML_ID'];
    }
}

/** @var CMain $APPLICATION */
global $APPLICATION;

$APPLICATION->IncludeComponent(
    'redsign:delivery.calculator',
    $templateName,
    $params,
    false
);
