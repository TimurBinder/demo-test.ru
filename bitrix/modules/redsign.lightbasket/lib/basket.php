<?php

namespace Redsign\LightBasket;

use Bitrix\Main;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Event;
use Redsign\LightBasket\Interfaces;

class Basket
{
    private static Interfaces\BasketProviderInterface $basketProvider;
    private Interfaces\BasketCollectionInterface $items;
    private array $price = [];

    public function __construct()
    {
        self::$basketProvider = self::getProvider();

        $this->items = BasketBuilder::createCollection();
    }

    public function load(): void
    {
        // @phpstan-ignore-next-line
        $basketItems = self::$basketProvider->getItems();
        $this->setItems($basketItems);
    }

    public function saveCollection(): void
    {
        // @phpstan-ignore-next-line
        self::$basketProvider->saveCollection($this->items);
    }

    public function getItems(): Interfaces\BasketCollectionInterface
    {
        return $this->items;
    }

    public function calculate(): void
    {
        $this->price = [];

        foreach ($this->getItems() as $item) {
            $priceKey = Tools::findPriceByCurrency($item->getCurrency(), $this->price);
            if ($priceKey !== -1) {
                $this->price[$priceKey]['DISCOUNT_PRICE'] += $item->getFullPrice();
                $this->price[$priceKey]['PRICE'] += $item->getPrice() * $item->getQuantity();
            } else {
                $this->price[] = [
                    'CURRENCY' => $item->getCurrency(),
                    'PRICE' => $item->getPrice() * $item->getQuantity(),
                    'DISCOUNT_PRICE' => $item->getFullPrice(),
                ];
            }
        }
    }

    public function getPrice(): array
    {
        return $this->price;
    }

    public static function getItem(int $id): ?Interfaces\BasketItemInterface
    {
        self::$basketProvider = self::getProvider();

        return self::$basketProvider->getItemById($id);
    }

    public function setItems(Interfaces\BasketCollectionInterface $items): self
    {
        $this->items = $items;
        $this->calculate();

        return $this;
    }

    /**
     * @param array|Interfaces\BasketItemInterface $item
     *
     * @return bool
     */
    public static function add($item): bool
    {
        $basketItem = is_array($item) ? BasketBuilder::createItem($item) : $item;

        if (!($basketItem instanceof Interfaces\BasketItemInterface)) {
            throw new \Exception('Undefined type of item');
        }

        self::$basketProvider = self::getProvider();

        $existingItem = self::$basketProvider->getExistingItem($basketItem);

        if ($existingItem) {
            $basketItemQuantity = $basketItem->getQuantity();
            $existingItem->incrementQuantity($basketItemQuantity);

            return self::update($existingItem);
        } else {
            $eventResult = self::triggerEvent('NEW_ITEM', [], $basketItem);

            if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
                return false;
            }

            return self::$basketProvider->add($basketItem);
        }
    }

    public static function update(Interfaces\BasketItemInterface $basketItem): bool
    {
        // $basketItem = is_array($item) ? BasketBuilder::createItem($item) : $item;

        if (!($basketItem instanceof Interfaces\BasketItemInterface)) {
            throw new \Exception('Undefined type of item');
        }
        $eventResult = self::triggerEvent('UPDATE_ITEM', [], $basketItem);

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return false;
        }

        self::$basketProvider = self::getProvider();

        return self::$basketProvider->update($basketItem);
    }

    public static function remove(int $id): bool
    {
        $eventResult = self::triggerEvent('NEW_ITEM', ['ID' => $id]);

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return false;
        }

        self::$basketProvider = self::getProvider();

        return self::$basketProvider->removeById($id);
    }

    public static function clear(): void
    {
        $eventResult = self::triggerEvent('CLEAR');

        if ($eventResult == \Bitrix\Main\EventResult::ERROR) {
            return;
        }

        self::$basketProvider = self::getProvider();

        self::$basketProvider->clear();
    }

    public static function getProvider(): Interfaces\BasketProviderInterface
    {
        if (!isset(self::$basketProvider)) {
            $basketProviderName = Option::get('redsign.lightbasket', 'basket_provider');
            if ($basketProviderName == 'database') {
                self::$basketProvider = new BasketDatabaseProvider();
            } else {
                self::$basketProvider = new BasketSessionProvider();
            }
        }

        return self::$basketProvider;
    }

    public static function setProvider(Interfaces\BasketProviderInterface $basketProvider): void
    {
        self::$basketProvider = $basketProvider;
    }

    private static function triggerEvent(
        string $type,
        ?array $params = [],
        Interfaces\BasketItemInterface &$basketItem = null
    ): int {
        $params = is_array($params) ? $params : [];
        $params['TYPE'] = $type;

        if ($basketItem) {
            $params['BASKET_ITEM'] = $basketItem;
        }

        $event = new Event('redsign.lightbasket', 'onLightBasketUpdate', $params);
        $event->send();

        $eventResult = \Bitrix\Main\EventResult::SUCCESS;
        if ($event->getResults()) {
            /** @var Main\EventResult $eventResult */
            foreach ($event->getResults() as $eventResult) {
                if ($basketItem) {
                    if ($eventResult->getType() == \Bitrix\Main\EventResult::SUCCESS) {
                        $newBasketItem = $event->getParameter('BASKET_ITEM');
                        if ($newBasketItem instanceof Interfaces\BasketItemInterface) {
                            $basketItem = $newBasketItem;
                        }
                    }
                }
                $eventResult = $eventResult->getType();
            }
        }

        return $eventResult;
    }
}
