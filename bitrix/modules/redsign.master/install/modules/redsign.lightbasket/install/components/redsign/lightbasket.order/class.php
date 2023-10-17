<?php

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;
use \Bitrix\Iblock;
use \Redsign\LightBasket\Basket;
use \Bitrix\Main\Context;
use \Bitrix\Main\Mail;

class LightBasketOrder extends CBitrixComponent
{
    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }

    public function onPrepareComponentParams($params)
    {
        $this->arResult['MESSAGES'] = array(
            'ERROR' => array(),
            'SUCCESS' => array(),
        );

        $this->arResult['ORDER_SUCCESS'] = 'N';
        $this->arResult['ORDER_ID'] = null;

        $defaultParams = array(
            'PATH_TO_CART' => '/cart/',
            'IBLOCK_TYPE' => 'lightbasket',
            'IBLOCK_ID' => '',
            'FIELDS_PROPS' => array(),
            'ITEMS_PROP' => '',
            'CATALOG_IBLOCK_TYPE' => 'catalog',
            'CATALOG_IBLOCK_IBLOCK' => '',
            'CATALOG_PROPS' => array(),
            'SHOW_CONFIRM' => 'N'
        );

        return array_merge($defaultParams, $params);
    }

    public function getFields()
    {
        $this->arResult['FIELDS'] = array();
        if (!is_array($this->arParams['FIELDS_PROPS']) || count($this->arParams['FIELDS_PROPS']) < 1) {
            return false;
        }

        $propertyIterator = Iblock\PropertyTable::getList(array(
            'filter' => array(
                '=CODE' => $this->arParams['FIELDS_PROPS'],
                '=IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                '=ACTIVE' => 'Y',
            ),
            'order' => array('SORT' => 'ASC'),
        ));

        while ($arProperty = $propertyIterator->fetch()) {
            if ($arProperty['PROPERTY_TYPE'] != 'S' && $arProperty['PROPERTY_TYPE'] != 'L') {
                continue;
            }

            if ($arProperty['PROPERTY_TYPE'] == 'L') {
                $propertyEnumIterator = Iblock\PropertyEnumerationTable::getList(array('filter' => array('=PROPERTY_ID' => $arProperty['ID'])));
                $arProperty['VALUES'] = $propertyEnumIterator->fetchAll();
            }

            $arProperty['CURRENT_VALUE'] = '';
            $this->arResult['FIELDS'][] = $arProperty;
        }
    }

    protected function getCatalogProperties()
    {
        $this->arResult['CATALOG_PROPERTIES'] = array();
        if (!is_array($this->arParams['CATALOG_PROPS']) || count($this->arParams) < 0) {
            return;
        }
        $propertyIterator = Iblock\PropertyTable::getList(array(
            'filter' => array(
                '=CODE' => $this->arParams['CATALOG_PROPS'],
            ),
        ));

        while ($arProperty = $propertyIterator->fetch()) {
            $this->arResult['CATALOG_PROPERTIES'][$arProperty['CODE']] = $arProperty;
        }
    }

    public function getItems()
    {
        $basket = new Basket();
        $basket->load();

        $basketItems = $basket->getItems();
        $arItems = array();

        foreach ($basketItems as $basketItem) {
            $itemId = $basketItem->getId();

            $arItems[$basketItem->getElementId()] = array(
                'ID' => $itemId,
                'PRODUCT_ID' => $basketItem->getElementId(),
                'PRICE' => $basketItem->getPrice(),
                'PRICE_FORMATTED' => number_format($basketItem->getPrice(), $this->arParams['PRICE_DECIMALS'], '.', ' '),
                'DISCOUNT_PRICE' => $basketItem->getDiscountPrice(),
                'DISCOUNT_PRICE_FORMATTED' => number_format($basketItem->getDiscountPrice(), $this->arParams['PRICE_DECIMALS'], '.', ' '),
                'DISCOUNT' => $basketItem->getDiscount(),
                'DISCOUNT_FORMATTED' => number_format($basketItem->getDiscount(), $this->arParams['PRICE_DECIMALS'], '.', ' '),
                'CURRENCY' => $basketItem->getCurrency(),
                'FULL_PRICE' => $basketItem->getFullPrice(),
                'FULL_PRICE_FORMATTED' => number_format($basketItem->getFullPrice(), $this->arParams['PRICE_DECIMALS'], '.', ' '),
                'QUANTITY' => $basketItem->getQuantity(),
            );
        }

        $arSelect = array(
            'ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'IBLOCK_ID', 'IBLOCK.DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID', 'CODE',
        );

        $rsIBlockElement = Iblock\ElementTable::getList(array(
            'filter' => array(
                '=ID' => array_keys($arItems),
            ),
            'select' => $arSelect,
        ));

        while ($arIBlockElement = $rsIBlockElement->fetch()) {
            if (isset($arItems[$arIBlockElement['ID']])) {
                $arItem = &$arItems[$arIBlockElement['ID']];

                Iblock\Component\Tools::getFieldImageData(
                    $arIBlockElement,
                    array('PREVIEW_PICTURE'),
                    Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION
                );
                $arItem['IBLOCK_ID'] = $arIBlockElement['IBLOCK_ID'];
                $arItem['NAME'] = $arIBlockElement['NAME'];
                $arItem['PREVIEW_PICTURE'] = $arIBlockElement['PREVIEW_PICTURE'];
                $arItem['~PREVIEW_TEXT'] = $arIBlockElement['PREVIEW_TEXT'];
                $arItem['PREVIEW_TEXT'] = htmlspecialcharsbx($arItem['~PREVIEW_TEXT']);
                $arItem['DETAIL_PAGE_URL'] = \CIBlock::ReplaceDetailUrl(
                    $arIBlockElement['IBLOCK_ELEMENT_IBLOCK_DETAIL_PAGE_URL'],
                    $arIBlockElement,
                    true,
                   'E'
                );

                $this->getItemProperties($arItem);
            }
        }
        unset($arItem);

        $this->arResult['ITEMS'] = $arItems;
        $this->arResult['PRICE'] = $this->formatPrices($basket->getPrice());
    }

    protected function formatPrices($arPrices) {
        foreach($arPrices as &$arPrice) {
            $arPrice['PRICE_FORMATTED']  = number_format($arPrice['PRICE'], $this->arParams['PRICE_DECIMALS'], '.', ' ');
            $arPrice['DISCOUNT_PRICE_FORMATTED']  = number_format($arPrice['DISCOUNT_PRICE'], $this->arParams['PRICE_DECIMALS'], '.', ' ');
        }
        unset($arPrice);

        return $arPrices;
    }

    protected function getItemProperties(&$arItem)
    {
        $arItem['PROPERTIES'] = array();

        if (is_array($this->arParams['CATALOG_PROPS']) && count($this->arParams['CATALOG_PROPS'])) {
            $itemProperty = new Iblock\Template\Entity\ElementProperty($arItem['PRODUCT_ID']);
            $itemProperty->setIblockId($arItem['IBLOCK_ID']);

            foreach ($this->arParams['CATALOG_PROPS'] as $propName) {
                if (strlen(trim($propName)) > 0) {
                    $arItem['PROPERTIES'][$propName] = $itemProperty->getField(strtolower($propName));
                }
            }
        }
    }

    protected function save()
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();

        if ($request->isPost() && check_bitrix_sessid() && !empty($this->arParams['ITEMS_PROP'])) {
            if($this->arParams['SHOW_CONFIRM'] == 'Y' && !$request->getPost('ORDER_CONFIRM_PDP')) {
                $this->arResult['MESSAGES']['ERROR'][] = Loc::getMessage('CONFIRM_CONDITION_ERROR');
                return;
            }

            $orderProperties = array();
            $orderProperties[$this->arParams['ITEMS_PROP']] = array();
            foreach ($this->arResult['ITEMS'] as $arBasketItem) {
                $orderProperties[$this->arParams['ITEMS_PROP']][] = $arBasketItem['PRODUCT_ID'];
            }

            foreach ($this->arResult['FIELDS'] as &$arField) {
                $orderProperties[$arField['CODE']] = $arField['CURRENT_VALUE'] = $request->getPost('FIELD_'.$arField['CODE']);
            }
            unset($arField);

            $el = new CIBlockElement();

            $orderDate = ConvertTimeStamp(false, 'FULL');

            $orderProducts = '';
            foreach($this->arResult['ITEMS'] as $arItem) {
                $orderProducts .= $arItem['NAME'].' - '.$arItem['DISCOUNT_PRICE_FORMATTED'].'x'.$arItem['QUANTITY'].'<br>';
            }

            $orderPrice = '';
            foreach($this->arResult['PRICE'] as $arPrice) {
                $orderPrice = str_replace("#", $arPrice['DISCOUNT_PRICE_FORMATTED'], $arPrice['CURRENCY']).' ';
            }

            $orderText = Loc::getMessage('NEW_ORDER_TEXT');
            $orderText = str_replace("#DATE#", $orderDate, $orderText);
            $orderText = str_replace("#PRODUCTS#", $orderProducts, $orderText);
            $orderText = str_replace("#FULL_PRICE#", $orderPrice, $orderText);

            $orderId = $el->Add(array(
                'IBLOCK_SECTION_ID' => false,
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'NAME' => str_replace("#DATE#", $orderDate, Loc::getMessage('NEW_ORDER')),
                'ACTIVE' => 'Y',
                'PROPERTY_VALUES' => $orderProperties,
                'PREVIEW_TEXT' => $orderText,
                'PREVIEW_TEXT_TYPE' => 'html'
            ));

            if ($orderId) {
                $this->arResult['ORDER_SUCCESS'] = 'Y';
                $this->arResult['ORDER_ID'] = $orderId;
                $this->sendMail($orderId, $orderProducts, $orderPrice, $orderProperties);
                Basket::clear();

                if (empty($_SESSION['USER_ORDERS']) || !is_array($_SESSION['USER_ORDERS'])) {
                    $_SESSION['USER_ORDERS'] = array();
                }

                $_SESSION['USER_ORDERS'][] = $orderId;

                $orderLink = $this->getOrderLink($orderId);
                LocalRedirect($orderLink);
            } else {
                $this->arResult['MESSAGES']['ERROR'] += explode('<br>', $el->LAST_ERROR);
            }
        }
    }

    protected function getOrderLink($id)
    {
        $context = Context::getCurrent();
        $server = $context->getServer();
        $sUri = $server->getRequestUri();

        $uri = new \Bitrix\Main\Web\Uri($sUri);
        $uri->addParams(array(
          'order_id' => $id,
      ));

        return $uri->getUri();
    }

    protected function isOrderSuccess()
    {
        $context = Context::getCurrent();
        $request = $context->getRequest();

        $orderId = $request->getQuery('order_id');
        if ($orderId && is_array($_SESSION['USER_ORDERS']) && in_array($orderId, $_SESSION['USER_ORDERS'])) {
            $this->arResult['ORDER_ID'] = $orderId;
            $this->arResult['ORDER_SUCCESS'] = 'Y';

            return true;
        }

        return false;
    }

    protected function sendMail($id, $products, $fullPrice, $props = array()) {
      $data = array();
      $data['EVENT_NAME'] = ($this->arParams['EVENT_NAME']) ? $this->arParams['EVENT_NAME'] : 'RS_NEW_ORDER';
      $data['LID'] = SITE_ID;
      $data['C_FIELDS'] = $props;
      $data['C_FIELDS']['ORDER_ID'] = $id;
      $data['C_FIELDS']['PRODUCTS'] = $products;
      $data['C_FIELDS']['PRICE'] = $fullPrice;

      Mail\Event::send($data);
    }

    public function executeComponent()
    {
        if (!Loader::includeModule('redsign.lightbasket') || !Loader::includeModule('iblock')) {
            return false;
        }

        $this->setFramemode(false);

        if (!$this->isOrderSuccess()) {
            $this->getCatalogProperties();
            $this->getItems();

            if (!is_array($this->arResult['ITEMS']) == count($this->arResult['ITEMS'])) {
                LocalRedirect($this->arParams['PATH_TO_CART']);
            }

            $this->getFields();
            $this->save();
        }
        $this->includeComponentTemplate();
    }
}
