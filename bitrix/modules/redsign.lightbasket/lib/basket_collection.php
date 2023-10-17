<?php

namespace Redsign\LightBasket;

class BasketCollection implements Interfaces\BasketCollectionInterface
{
    private array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    public function isExists(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    public function get(string $key): ?Interfaces\BasketItemInterface
    {
        return $this->isExists($key) ? $this->items[$key] : null;
    }

    public function push(Interfaces\BasketItemInterface $item): void
    {
        $this->items[] = $item;
    }

    public function remove(string $key): void
    {
        if ($this->isExists($key)) {
            unset($this->items[$key]);
        }
    }

    public function contains(Interfaces\BasketItemInterface $containsItem): ?string
    {
        foreach ($this->items as $key => $collectionItem) {
            if ($collectionItem->compare($containsItem)) {
                return $key;
            }
        }

        return null;
    }

    public function toArray(): array
    {
        $arrayItems = [];

        foreach ($this->items as $item) {
            $arrayItems[] = $item->toArray();
        }

        return $arrayItems;
    }
}
