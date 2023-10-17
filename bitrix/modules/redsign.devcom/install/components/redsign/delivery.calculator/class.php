<?php

namespace Redsign\Components;

use Bitrix\Currency;
use Bitrix\Main;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Sale;
use Bitrix\Sale\PropertyValue;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true)
    die();


Loc::loadMessages(__FILE__);

class DeliveryCalculator extends \CBitrixComponent
{
    protected CurrentUser $user;
    protected array $cacheKey = [];
    protected array $cacheAddon = [];

    protected string $sCurrency;
    protected string $sLocationFrom;
    protected string $sLocationTo;
    protected string $sLocationZip;

    protected Sale\Shipment $shipment;
    protected array $product = [];
    protected int $elementId;

    protected array $arDeliveryServices;

    /**
     * @param \CBitrixComponent|null $component
     */
    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->user = CurrentUser::get();
    }

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        $arParams = parent::onPrepareComponentParams($arParams);

        $arParams['ELEMENT_ID'] = (int) $arParams['ELEMENT_ID'];
        $arParams['QUANTITY'] = (float) $arParams['QUANTITY'];
        $arParams['PREFIX'] = isset($arParams['PREFIX']) ? $arParams['PREFIX'] : '';

        $this->arResult['ORIGINAL_PARAMS'] = $arParams;

        return $arParams;
    }

    protected function obtainProduct(): void
    {
        $arPrice = \CCatalogProduct::GetOptimalPrice(
            $this->arParams['ELEMENT_ID'],
            $this->arParams['QUANTITY'],
            $this->user->getUserGroups()
        );

        if (isset($arPrice['RESULT_PRICE'])) {
            $this->product['BASE_PRICE'] = $arPrice['RESULT_PRICE']['BASE_PRICE'];
            $this->product['DISCOUNT_PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
            $this->product['PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
            $this->product['CURRENCY'] = $arPrice['PRICE']['CURRENCY'];
        }

        $product = \Bitrix\Catalog\ProductTable::getRowById($this->arParams['ELEMENT_ID']);

        if (!$product)
            throw new SystemException('ELEMENT NOT FOUND');

        $this->product['WEIGHT'] = $product['WEIGHT'];
        $this->product['QUANTITY'] = $this->arParams['QUANTITY'];
        $this->product['DIMENSIONS'] = serialize([
            'HEIGHT' => $product['HEIGHT'],
            'WIDTH' => $product['WIDTH'],
            'LENGTH' => $product['LENGTH'],
        ]);
    }

    protected function obtainShipment(): void
    {
        $userId = $this->user->getId() ?: \CSaleUser::GetAnonymousUserID();

        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var Sale\Order $orderClassName */
        $orderClassName = $registry->getOrderClassName();
         // @phpstan-ignore-next-line
        $order = $orderClassName::create($this->getSiteId(), $userId);

        $this->initProperties($order);
        $this->initBasket($order);

        $this->shipment = $this->initShipment($order);
    }

    protected function obtainDeliveryServices(): void
    {
        $arDeliveryServicesAll = Sale\Delivery\Services\Manager::getRestrictedObjectsList($this->shipment);

        if (is_array($this->arParams['DELIVERY']) && count($this->arParams['DELIVERY']) > 0) {
            foreach ($arDeliveryServicesAll as $deliveryId => $deliveryObj) {
                if (!in_array($deliveryObj->getId(), $this->arParams['DELIVERY'])) {
                    unset($arDeliveryServicesAll[$deliveryId]);
                }
            }
        }

        $this->arDeliveryServices = $arDeliveryServicesAll;
    }

    /**
     * @param string $locationCode
     * @return array|false
     */
    protected function getLocationData(string $locationCode)
    {
        if ($locationCode == '')
            return false;

        $locationIterator = Sale\Location\LocationTable::getList([
            'filter' => [
                '=CODE' => $locationCode,
                '=NAME.LANGUAGE_ID' => LANGUAGE_ID,
                '=TYPE.NAME.LANGUAGE_ID' => LANGUAGE_ID,
            ],
            'select' => [
                'ID',
                'CODE',
                'LATITUDE',
                'LONGITUDE',
                'LOCATION_TYPE' => 'TYPE.NAME.NAME',
                'LOCATION_NAME' => 'NAME.NAME',
            ],
        ]);


        return $locationIterator->fetch();
    }

    protected function prepareData(): void
    {
        $this->elementId = $this->arParams['ELEMENT_ID'];
        $this->sCurrency = $this->arParams['CURRENCY'] ?: Currency\CurrencyManager::getBaseCurrency();
        $this->sLocationTo = $this->arParams['LOCATION_TO'] ?: '';
        $this->sLocationFrom = $this->arParams['LOCATION_FROM'] ?: '';
        $this->sLocationZip = $this->arParams['LOCATION_ZIP'] ?: '';

        $this->obtainProduct();
        $this->obtainShipment();
        $this->obtainDeliveryServices();
    }

    protected function formatResult(): void
    {
        $this->arResult['DELIVERIES'] = [];
        foreach ($this->arDeliveryServices as $deliveryId => $arDeliveryObj) {
            $arDelivery = [];

            $arDelivery['NAME'] = $arDeliveryObj->getNameWithParent();
            $arDelivery['DESCRIPTION'] = $arDeliveryObj->getDescription();
            $arDelivery['SORT'] = $arDeliveryObj->getSort();
            $arDelivery['PICTURE_PATH'] = $arDeliveryObj->getLogotipPath();

            $this->shipment->setField('DELIVERY_ID', $deliveryId);
            $calcResult = $arDeliveryObj->calculate($this->shipment);
            $arDelivery['CALCULATION'] = [];
            $arDelivery['CALCULATION']['IS_SUCCESS'] = $calcResult->isSuccess();
            $arDelivery['CALCULATION']['PRICE'] = $calcResult->getPrice();
            $arDelivery['CALCULATION']['FORMAT_PRICE'] = CurrencyFormat($calcResult->getPrice(), $this->sCurrency);
            $arDelivery['CALCULATION']['DESCRIPTION'] = $calcResult->getDescription();
            $arDelivery['CALCULATION']['PERIOD'] = $calcResult->getPeriodDescription();
            $arDelivery['CALCULATION']['PACKS'] = $calcResult->getPacksCount();

            $this->arResult['DELIVERIES'][] = $arDelivery;
        }

        $this->arResult['PRODUCT'] = $this->product;
        $this->arResult['PRODUCT']['FULL_PRICE'] = $this->product['PRICE'] * $this->product['QUANTITY'];
        $this->arResult['PRODUCT']['PRICE_FORMAT'] = CurrencyFormat($this->product['PRICE'], $this->product['CURRENCY']);
        $this->arResult['PRODUCT']['FULL_PRICE_FORMAT'] = CurrencyFormat($this->arResult['PRODUCT']['FULL_PRICE'], $this->product['CURRENCY']);

        $this->arResult['CURRENCY'] = $this->sCurrency;
        $this->arResult['LOCATION_FROM'] = $this->getLocationData($this->sLocationFrom);
        $this->arResult['LOCATION_TO'] = $this->getLocationData($this->sLocationTo);
    }

    protected function extractDataFromCache(): bool
    {
        return !($this->startResultCache(false, $this->getAdditionalCacheId(), $this->getComponentCachePath()));
    }

    protected function getAdditionalCacheId(): array
    {
        $cacheId = [
        ];

        return $cacheId;
    }

    protected function getComponentCachePath(): string
    {
        return '/' . $this->getSiteId() . $this->getRelativePath();
    }

    protected function putDataToCache(): void
    {
    }

    protected function abortDataCache(): void
    {
        $this->abortResultCache();
    }

    protected function checkModules(): void
    {
        $needModules = ['iblock', 'sale', 'catalog', 'currency'];
        foreach ($needModules as $module) {
            if (!Loader::includeModule($module)) {
                throw new SystemException(
                    Loc::getMessage('RS_DEVCOM_RDC_MODULE_NOT_INSTALLED', ['#MODULE_ID#' => $module]) ?: ''
                );
            }
        }
    }

    public function executeComponent(): void
    {
        try {
            $this->checkModules();

            if (!$this->extractDataFromCache()) {
                $this->prepareData();
                $this->formatResult();

                $this->setResultCacheKeys([]);
                $this->includeComponentTemplate();
                $this->putDataToCache();
            }
        } catch (\Throwable $e) {
            $this->abortDataCache();
            $this->__showError($e->getMessage(), $e->getCode());
        }
    }


    /**
     * @param Sale\Order $order
     */
    protected function initProperties(Sale\Order $order): void
    {
        $props = $order->getPropertyCollection();

        /** @var ?PropertyValue $loc */
        $loc = $props->getDeliveryLocation();
        if ($loc)
            $loc->setValue($this->sLocationTo);

        /** @var ?PropertyValue $loc */
        $loc = $props->getDeliveryLocationZip();
        if ($loc)
            $loc->setValue($this->sLocationZip);
    }

    /**
     * @param Sale\Order $order
     * @throws Main\ObjectNotFoundException
     */
    protected function initBasket(Sale\Order $order): void
    {
        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);

        /** @var Sale\Basket $basketClassName */
        $basketClassName = $registry->getBasketClassName();
        $basket = $basketClassName::create(SITE_ID);

        $basketItemClassName = $registry->getBasketItemClassName();
        $basketItem = $basketItemClassName::create($basket, 'catalog', $this->arParams['ELEMENT_ID']);

        $basketItem->setFields($this->product);
        $basket->addItem($basketItem);

        $order->setBasket($basket);
    }

    /**
     * @param Sale\Order $order
     * @return Sale\Shipment
     * @throws Main\ArgumentTypeException
     * @throws Main\NotSupportedException
     */
    public function initShipment(Sale\Order $order): Sale\Shipment
    {
        $shipmentCollection = $order->getShipmentCollection();
        $shipment = $shipmentCollection->createItem();
        $shipmentItemCollection = $shipment->getShipmentItemCollection();
        $shipment->setField('CURRENCY', $order->getCurrency());

        /** @var Sale\BasketItem $item */
        foreach ($order->getBasket() as $item) {
            /** @var Sale\ShipmentItem $shipmentItem */
            $shipmentItem = $shipmentItemCollection->createItem($item);
            $shipmentItem->setQuantity($item->getQuantity());
        }

        return $shipment;
    }
}
