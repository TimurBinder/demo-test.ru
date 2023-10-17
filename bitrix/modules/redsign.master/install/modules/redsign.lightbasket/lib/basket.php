<?php

namespace Redsign\LightBasket;

use Bitrix\Main\Event;

class Basket
{
    private $items;
    private static $basketProvider = null;

    private $price;
    private $discountPrice;

    public function __construct()
    {
        if (is_null(self::$basketProvider)) {
            static::$basketProvider = new BasketSessionProvider();
        }

        $this->items = BasketBuilder::createCollection();
    }

    public function load()
    {
        $basketItems = self::$basketProvider->getItems();
        $this->setItems($basketItems);
    }

    public function saveCollection()
    {
        self::$basketProvider->saveColelction($this->items);
    }

    public function getItems()
    {
        return $this->items;
    }

    public function calculate()
    {
        $this->price = array();

        foreach ($this->getItems() as $item) {
            $priceKey = Tools::findPriceByCurrency($item->getCurrency(), $this->price);
            if ($priceKey !== -1) {
                $this->price[$priceKey]['DISCOUNT_PRICE'] += $item->getFullPrice();
                $this->price[$priceKey]['PRICE'] += $item->getPrice() * $item->getQuantity();
            } else {
                $this->price[] = array(
                    'CURRENCY' => $item->getCurrency(),
                    'PRICE' => $item->getPrice() * $item->getQuantity(),
                    'DISCOUNT_PRICE' => $item->getFullPrice(),
                );
            }
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public static function getItem($id)
    {
        return static::$basketProvider->getItemById($id);
    }

    public function setItems(Interfaces\BasketCollectionInterface $items)
    {
        $this->items = $items;
        $this->calculate();
    }

    public static function add($item)
    {
        $basketItem = is_array($item) ? BasketBuilder::createItem($item) : $item;

        if (!($basketItem instanceof Interfaces\BasketItemInterface)) {
            throw new \Exception('Undefined type of item');

            return false;
        }

        $existingItem = static::$basketProvider->getExistingItem($basketItem);

        if ($existingItem) {
            $basketItemQuantity = $basketItem->getQuantity();
            $existingItem->incrementQuantity($basketItemQuantity);

            return static::update($existingItem);
        } else {
            $eventResult = static::triggerEvent('NEW_ITEM', array(), $basketItem);

            if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
                return false;
            }

            return static::$basketProvider->add($basketItem);
        }
    }

    public function update($item)
    {
        $basketItem = is_array($item) ? BasketBuilder::createItem($item) : $item;

        if (!($basketItem instanceof Interfaces\BasketItemInterface)) {
            throw new \Exception('Undefined type of item');

            return false;
        }
        $eventResult = static::triggerEvent('UPDATE_ITEM', array(), $basketItem);

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return false;
        }

        return static::$basketProvider->update($basketItem);
    }

    public static function remove($id)
    {
        $eventResult = static::triggerEvent('NEW_ITEM', array('ID' => $id));

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return false;
        }

        return static::$basketProvider->removeById($id);
    }

    public static function clear()
    {
        $eventResult = static::triggerEvent('CLEAR');

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return;
        }

        static::$basketProvider->clear();
    }

    public static function setProvider(Interfaces\BasketProviderInterface $basketProvider)
    {
        static::$basketProvider = $basketProvider;
    }

    private static function triggerEvent($type, array $params = array(), &$basketItem = null)
    {
        if (!is_array($params)) {
            $params = array();
        }

        $params['TYPE'] = $type;

        if ($basketItem) {
            $params['BASKET_ITEM'] = $basketItem;
        }

        $event = new Event('redsign.lightbasket', 'onLightBasketUpdate', $params);
        $event->send();

        $eventResult = \Bitrix\Main\EventResult::SUCCESS;

        foreach ($event->getResults() as $eventResult) {
            if ($basketItem) {
                if ($eventResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
                    $newBasketItem = $eventResult->getParameter('BASKET_ITEM');
                    if ($newBasketItem instanceof Interfaces\BasketItemInterface) {
                        $basketItem = $newBasketItem;
                    }
                }
            }
            $eventResult = $eventResult->getType();
        }

        return $eventResult;
    }
}
