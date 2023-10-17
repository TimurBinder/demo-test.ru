<?php

namespace Redsign\LightBasket;

class BasketSessionProvider implements Interfaces\BasketProviderInterface
{
    //const SESSION_NAME = 'REDSIGN_LIGHTBASKET_'.SITE_ID;
    private $sessionName;
    private $session;

    public function __construct()
    {
        if (!isset($_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID])) {
            $_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID] = array();
        }
        $this->session = &$_SESSION['REDSIGN_LIGHTBASKET_'.SITE_ID];
    }

    public function getItems()
    {
        $collection = BasketBuilder::createCollection();

        if (isset($this->session) && is_array($this->session)) {
            foreach ($this->session as $item) {
                $basketItem = BasketBuilder::createItem($item);
                $collection->push($basketItem);
            }
        }

        return $collection;
    }

    public function getItemById($id)
    {
        foreach ($this->session as $item) {
            if ($item['ID'] == $id) {
                return BasketBuilder::createItem($item);
            }
        }

        return false;
    }

    public function saveCollection(Interfaces\BasketCollectionInterface $items)
    {
        if (!isset($this->session) || !is_array($this->session)) {
            $this->session = array();
        }

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

    public function add(Interfaces\BasketItemInterface $item)
    {
        if (!isset($this->session) || !is_array($this->session)) {
            $this->session = array();
        }

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

    public function update(Interfaces\BasketItemInterface $item)
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

    public function removeById($id)
    {
        foreach ($this->session as $i => $savedItem) {
            if ($savedItem['ID'] == $id) {
                unset($this->session[$i]);

                return true;
            }
        }

        return false;
    }

    public function clear()
    {
        $this->session = array();
    }

    public function findItem(array $options)
    {
        $items = $this->session;
        $findItem = false;
        foreach ($items as $item) {
            $findItem = $item;
            foreach ($options as $key => $option) {
                if ($item[$key] != $option) {
                    $findItem = false;
                    break;
                }
            }

            if ($findItem !== false) {
                $findItem = BasketBuilder::createItem($findItem);
                break;
            }
        }

        return $findItem;
    }

    public function getExistingItem(Interfaces\BasketItemInterface $item)
    {
        $item = $this->findItem(array(
            'ELEMENT_ID' => $item->getElementId(),
            'PRICE' => $item->getPrice(),
            'CURRENCY' => $item->getCurrency(),
        ));

        return $item;
    }

    public function isItemExist($id)
    {
        $item = $this->findItem(array('ID' => $id));

        return $item ? true : false;
    }
}
