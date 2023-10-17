<?php

namespace Redsign\LightBasket;

use Redsign\LightBasket\Interfaces\BasketItemInterface;

class BasketSessionProvider implements Interfaces\BasketProviderInterface
{
    private array $session;

    public function __construct()
    {
        if (
            !isset($_SESSION['REDSIGN_LIGHTBASKET_' . SITE_ID])
            || !is_array($_SESSION['REDSIGN_LIGHTBASKET_' . SITE_ID])
        ) {
            $_SESSION['REDSIGN_LIGHTBASKET_' . SITE_ID] = [];
        }

        $this->session = &$_SESSION['REDSIGN_LIGHTBASKET_' . SITE_ID];
    }

    public function getItems(): BasketCollection
    {
        $collection = BasketBuilder::createCollection();

        foreach ($this->session as $item) {
            $basketItem = BasketBuilder::createItem($item);
            $collection->push($basketItem);
        }

        return $collection;
    }

    public function getItemById(int $id): ?BasketItemInterface
    {
        foreach ($this->session as $item) {
            if ($item['ID'] == $id) {
                return BasketBuilder::createItem($item);
            }
        }

        return null;
    }

    public function saveCollection(Interfaces\BasketCollectionInterface $items): void
    {
        foreach ($items as $item) {
            $isExists = false;
            foreach ($this->session as $i => $savedItem) {
                if ($item->getId() == $savedItem['ID']) {
                    $this->session[$i] = $item->toArray();
                    $isExists = true;
                    break;
                }
            }

            if (!$isExists) {
                $this->add($item);
            }
        }
    }

    public function add(Interfaces\BasketItemInterface $item): bool
    {
        $itemId = $item->getId();

        if (empty($itemId)) {
            $itemId = count($this->session) + 1;
            $item->setId($itemId);
        } else {
            $isItemExist = $this->isItemExist($itemId);
            if ($isItemExist) {
                return false;
            }
        }

        $this->session[] = $item->toArray();

        return true;
    }

    public function update(Interfaces\BasketItemInterface $item): bool
    {
        $itemId = $item->getId();

        if (empty($itemId)) {
            return false;
        }

        foreach ($this->session as $i => $savedItem) {
            if ($savedItem['ID'] == $itemId) {
                $this->session[$i] = $item->toArray();

                return true;
            }
        }

        return false;
    }

    public function removeById(int $id): bool
    {
        foreach ($this->session as $i => $savedItem) {
            if ($savedItem['ID'] == $id) {
                unset($this->session[$i]);

                return true;
            }
        }

        return false;
    }

    public function findItem(array $options): ?BasketItemInterface
    {
        $findItem = null;
        foreach ($this->session as $item) {
            $find = true;
            foreach ($options as $key => $option) {
                if ($item[$key] != $option) {
                    $find = false;
                    break;
                }
            }

            if ($find) {
                $findItem = BasketBuilder::createItem($item);
                break;
            }
        }

        return $findItem;
    }

    public function clear(): void
    {
        $this->session = [];
    }

    public function getExistingItem(Interfaces\BasketItemInterface $item): ?BasketItemInterface
    {
        $item = $this->findItem([
            'ELEMENT_ID' => $item->getElementId(),
            'PRICE' => $item->getPrice(),
            'CURRENCY' => $item->getCurrency(),
        ]);

        return $item;
    }

    public function isItemExist(int $id): bool
    {
        $item = $this->findItem(['ID' => $id]);

        return $item ? true : false;
    }
}
