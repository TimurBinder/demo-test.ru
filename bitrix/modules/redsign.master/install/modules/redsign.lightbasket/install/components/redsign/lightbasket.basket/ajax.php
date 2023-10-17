<?php

define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true);

if (isset($_POST['siteId']) && is_string($_POST['siteId'])) {
    define('SITE_ID', $_POST['siteId']);
}

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

use \Bitrix\Main\Context;
use \Bitrix\Main\Localization\Loc;
use \Redsign\LightBasket;

Loc::loadMessages(__FILE__);

$arResult = array(
    'ERROR' => false,
    'SUCCESS' => false,
);

if (
    !\Bitrix\Main\Loader::includeModule('redsign.lightbasket') ||
    !\Bitrix\Main\Loader::includeModule('iblock')
) {
    $arResult['ERROR'] = Loc::getMessage('RS_LIGHTBASKET_MODULE_NOT_INSTALLED');
}

$context = Context::getCurrent();
$request = $context->getRequest();

$action = trim($request->get('action'));

if (!$arResult['ERROR'] && check_bitrix_sessid()) {
    switch ($action) {
        case 'add2basket':
            $productId = (int) $request->get('product_id');
            $quantity = (float) $request->get('quantity');

            if ($quantity <= 0) {
                $quantity = 1;
            }

            $arSelectProperties = LightBasket\Tools::getSelectParameters();
            $arSelect = array_merge(array('ID', 'NAME'), $arSelectProperties);

            $rs = \CIBlockElement::GetList(
                array('SORT' => 'ASC'),
                array('ACTIVE' => 'Y', 'ID' => $productId),
                false,
                false,
                $arSelect
            );

            $arElement = $rs->GetNext();

            $elementPrice = LightBasket\Tools::getPriceValue($arElement);
            if ($arElement) {
                $result = LightBasket\Basket::add(array(
                    'ELEMENT_ID' => $productId,
                    'PRICE' => $elementPrice,
                    'CURRENCY' => LightBasket\Tools::getCurrencyValue($arElement),
                    'QUANTITY' => $quantity,
                    'DISCOUNT' => LightBasket\Tools::getDiscountValue($arElement),
                ));

                if ($result) {
                    $arResult['SUCCESS'] = Loc::getMessage('RS_LIGHTBASKET_ADD_SUCCESS');
                } else {
                    $arResult['ERROR'] = Loc::getMessage('RS_LIGHTBASKET_ADD_FAILED');
                }
            } else {
                $arResult['ERROR'] = Loc::getMessage('RS_LIGHTBASKET_ADD_ELEMENT_NOT_FOUND');
            }

            break;
        case 'update':
            $arResult['DATA'] = array();

            $id = $request->get('id');
            $quantity = (float) $request->get('quantity');

            $basketItem = LightBasket\Basket::getItem($id);

            if ($basketItem) {
                if ($quantity > 0) {
                    $basketItem->setQuantity($quantity);
                }

                $result = LightBasket\Basket::update($basketItem);
                if ($result) {
                    $arResult['SUCCESS'] = Loc::getMessage('RS_LIGHTBASKET_UPDATE_SUCCESS');
                    $arResult['DATA'] = $basketItem->toArray();
                } else {
                    $arResult['ERROR'] = Loc::getMessage('RS_LIGHTBASKET_UPDATE_FAILED');
                }
            } else {
                $arResult['ERROR'] = Loc::getMessage('RS_LIGHTBASKET_UPDATE_FAILED');
            }
            break;
        case 'delete':
            $arResult['DATA'] = array();

            $id = $request->get('id');
            
            $basketItem = LightBasket\Basket::getItem($id);
            if ($basketItem) {
                $arResult['SUCCESS'] = LightBasket\Basket::remove($id);
                $arResult['DATA']['ID'] = $basketItem->getElementId();
            } else {
                $arResult['ERROR'] = true;
            }
            break;
        case 'clear':
            LightBasket\Basket::clear();
             
            $arResult['SUCCESS'] = true;
            break;
        case 'get':

        $templateName = $request->get('templateName');
        if (!$templateName) {
            break;
        }

        $_POST['arParams']['AJAX'] = 'Y';

        $APPLICATION->RestartBuffer();
        header('Content-Type: text/html; charset='.LANG_CHARSET);
        $APPLICATION->IncludeComponent('redsign:lightbasket.basket', $_POST['templateName'], $_POST['arParams']);
        die();

    }
}

if (strtolower(SITE_CHARSET) != 'utf-8') {
    $arResult = $APPLICATION->ConvertCharsetArray($arResult, SITE_CHARSET, 'utf-8');
}

header('Content-Type: application/json');
echo json_encode($arResult);

$context->getResponse()->flush();
die();
