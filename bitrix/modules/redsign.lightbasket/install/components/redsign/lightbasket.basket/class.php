<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Redsign\LightBasket\Basket;
use Bitrix\Iblock;

class RedsignLightBasketBasket extends CBitrixComponent
{
    private array $arMessages = [];

    public function onIncludeComponentLang(): void
    {
        Loc::loadMessages(__FILE__);
    }

    /**
     * @param array $params
     * @return array
     */
    public function onPrepareComponentParams($params): array
    {
        $params = parent::onPrepareComponentParams($params);

        $defaultParams = [
            'IBLOCK_TYPE' => 'catalog',
            'IBLOCK_ID' => '',
            'PROPS' => [],
            'PATH_TO_ORDER' => '',
            'USE_AJAX' => 'N',
            'PRICE_DECIMALS' => 0
        ];

        $this->arMessages = [
            'ERROR' => [],
            'SUCCESS' => [],
        ];

        return array_merge($defaultParams, $params);
    }

    protected function getProperties(): void
    {
        $this->arResult['PROPERTIES'] = [];
        if (!is_array($this->arParams['PROPS']) || count($this->arParams['PROPS']) < 1) {
            return;
        }
        $propertyIterator = Iblock\PropertyTable::getList([
            'filter' => [
                '=CODE' => $this->arParams['PROPS'],
            ],
        ]);

        while ($arProperty = $propertyIterator->fetch()) {
            $this->arResult['PROPERTIES'][$arProperty['CODE']] = $arProperty;
        }
    }

    protected function getResult(): void
    {
        $basket = new Basket();
        $basket->load();

        $basketItems = $basket->getItems();
        $arItems = [];

        foreach ($basketItems as $basketItem) {
            $itemId = $basketItem->getId();

            $arItems[$basketItem->getElementId()] = [
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
                'URL_TO_DELETE' => $this->getUrlToDelete($itemId),
            ];
        }

        $arSelect = [
            'ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'IBLOCK_ID', 'IBLOCK.DETAIL_PAGE_URL', 'IBLOCK_SECTION_ID', 'CODE',
        ];

        $rsIBlockElement = Iblock\ElementTable::getList([
            'filter' => [
                '=ID' => array_keys($arItems),
            ],
            'select' => $arSelect,
        ]);

        while ($arIBlockElement = $rsIBlockElement->fetch()) {
            if (isset($arItems[$arIBlockElement['ID']])) {
                $arItem = &$arItems[$arIBlockElement['ID']];

                Iblock\Component\Tools::getFieldImageData(
                    $arIBlockElement,
                    ['PREVIEW_PICTURE'],
                    Iblock\Component\Tools::IPROPERTY_ENTITY_SECTION
                );
                $arItem['IBLOCK_ID'] = $arIBlockElement['IBLOCK_ID'];
                $arItem['NAME'] = $arIBlockElement['NAME'];
                $arItem['PREVIEW_PICTURE'] = $arIBlockElement['PREVIEW_PICTURE'];
                $arItem['PREVIEW_TEXT'] = $arIBlockElement['PREVIEW_TEXT'];
                $arItem['~PREVIEW_TEXT'] = htmlspecialcharsbx($arItem['PREVIEW_TEXT']);
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
        $this->arResult['PATH_TO_CLEAR'] = $this->getPathToClear();
    }

    protected function formatPrices(array $arPrices): array
    {
        foreach ($arPrices as &$arPrice) {
            $arPrice['PRICE_FORMATTED'] = number_format($arPrice['PRICE'], $this->arParams['PRICE_DECIMALS'], '.', ' ');
            $arPrice['DISCOUNT_PRICE_FORMATTED'] = number_format($arPrice['DISCOUNT_PRICE'], $this->arParams['PRICE_DECIMALS'], '.', ' ');
        }
        unset($arPrice);

        return $arPrices;
    }

    protected function getItemProperties(array &$arItem): void
    {
        $arItem['PROPERTIES'] = [];

        if (is_array($this->arParams['PROPS']) && count($this->arParams['PROPS'])) {
            $itemProperty = new Iblock\Template\Entity\ElementProperty($arItem['PRODUCT_ID']);
            $itemProperty->setIblockId($arItem['IBLOCK_ID']);

            foreach ($this->arParams['PROPS'] as $propName) {
                $arItem['PROPERTIES'][$propName] = $itemProperty->getField(strtolower($propName));
            }
        }
    }

    protected function getPathToClear(): string
    {
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $server = $context->getServer();
        $sUri = $server->getRequestUri() ?: '';

        $uri = new \Bitrix\Main\Web\Uri($sUri);
        $uri->addParams([
            'action' => 'clear',
        ]);

        return $uri->getUri();
    }

    protected function getUrlToDelete(int $id): string
    {
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $server = $context->getServer();
        $sUri = $server->getRequestUri() ?: '';

        $uri = new \Bitrix\Main\Web\Uri($sUri);
        $uri->addParams([
            'action' => 'delete',
            'id' => $id,
        ]);

        return $uri->getUri();
    }

    protected function actions(): void
    {
        $arMessages = &$this->arMessages;

        $context = Context::getCurrent();
        $request = $context->getRequest();

        $action = $request->get('action');

        if ($action === 'delete') {
            $id = (int) $request->get('id');
            $result = Basket::remove($id);

            if ($result) {
                $arMessages['SUCCESS'][] = Loc::getMessage('RS_LIGHTBASKET_DELETE_SUCCESS');
            } else {
                $arMessages['ERROR'][] = Loc::getMessage('RS_LIGHTBASKET_DELETE_FAILED');
            }
        } elseif ($action === 'clear') {
            Basket::clear();
        }
    }

    /**
     * @inheritdoc
     */
    public function executeComponent()
    {
        if (!Loader::includeModule('redsign.lightbasket') || !Loader::includeModule('iblock')) {
            return false;
        }

        $this->actions();

        $this->getProperties();
        $this->getResult();
        $this->includeComponentTemplate();
    }
}
