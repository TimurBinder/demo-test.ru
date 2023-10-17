<?php

namespace Redsign\LightBasket;

class BasketCollection implements
\Countable,
\IteratorAggregate,
Interfaces\Arrayble,
Interfaces\BasketCollectionInterface
{
    private $items;

    public function __construct($items = array())
    {
        $this->items = $items;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function isExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function get($key)
    {
        return $this->isExists($key) ? $this->items[$key] : null;
    }

    public function push(Interfaces\BasketItemInterface $item)
    {
        $this->items[] = $item;
    }

    public function remove($key)
    {
        if (isExists($key)) {
            unset($this->items[$key]);
        }
    }

    public function contains(Interfaces\BasketItemInterface $containsItem)
    {
        foreach ($this->items as $key => $collectionItem) {
            if ($collectionItem->compare($containsItem)) {
                return $key;
            }
        }

        return false;
    }

    public function toArray()
    {
        $arrayItems = array();

        foreach ($this->items as $item) {
            $arrayItems[] = $item->toArray();
        }

        return $arrayItems;
    }
}
