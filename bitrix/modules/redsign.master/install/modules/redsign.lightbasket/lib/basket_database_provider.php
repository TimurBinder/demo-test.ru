<?php

namespace Redsign\LightBasket;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;

class BasketDatabaseProvider implements Interfaces\BasketProviderInterface
{
    private $userId = 0;
    private $cookieKey;
    private $request;

    public function __construct()
    {
        $this->cookieKey = 'REDSIGN_LIGHTBASKET_USER_CODE_'.SITE_ID;
        $this->request = Application::getInstance()->getContext()->getRequest();
    }

    public function getUserId()
    {
        if ($this->userId !== 0) {
            return $this->userId;
        }

        $userCode = Application::getInstance()->getContext()->getRequest()->getCookie($this->cookieKey);

        if (is_null($userCode)) {
            $userCode = md5(time().randString(10));
            $cookie = new Cookie($this->cookieKey, $userCode);

            Application::getInstance()->getContext()->getResponse()->addCookie($cookie);

            $result = Entity\BasketUserTable::add(array(
                'CODE' => $userCode,
            ));

            if (!$result->isSuccess()) {
                return 0;
            }
        }

        $basketUser = Entity\BasketUserTable::getRowByCode($userCode);
        if ($basketUser) {
            $this->userId = $basketUser['ID'];
        } else {
            $result = Entity\BasketUserTable::add(array(
              'CODE' => $userCode,
          ));

            $this->userId = $result->getId();
        }

        return $this->userId;
    }

    public function getItems()
    {
        $basketCollection = BasketBuilder::createCollection();
        $userId = $this->getUserId();

        $rsBasketItems = Entity\BasketItemTable::getForUser($userId);
        while ($item = $rsBasketItems->fetch()) {
            $basketItem = BasketBuilder::createItem($item);
            $basketCollection->push($basketItem);
        }

        return $basketCollection;
    }

    public function getItemById($id)
    {
        $item = Entity\BasketItemTable::getRowById($id);

        if ($item) {
            return BasketBuilder::createItem($item);
        }

        return false;
    }

    public function saveCollection(Interfaces\BasketCollectionInterface $items)
    {
        foreach ($item as $item) {
            $itemId = $item->getId();

            if ($this->isItemExist($item)) {
                $this->update($item);
            } else {
                $this->add($item);
            }
        }
    }

    public function add(Interfaces\BasketItemInterface $item)
    {
        $itemArray = $this->prepareItem($item);

        if (!$itemArray) {
            return false;
        }

        $result = Entity\BasketItemTable::add($itemArray);

        return $result->isSuccess();
    }

    public function update(Interfaces\BasketItemInterface $item)
    {
        $itemId = $item->getId();

        if (empty($itemId)) {
            return false;
        }

        $userId = $this->getUserId();
        $itemArray = $this->prepareItem($item);

        $result = Entity\BasketItemTable::update($itemId, $itemArray);

        return $result->isSuccess();
    }

    public function removeById($id)
    {
        return Entity\BasketItemTable::delete($id);
    }

    public function findItem(array $options)
    {
        $filterArray = array();
        foreach ($options as $key => $option) {
            $filterArray['='.$key][] = $option;
        }
        $filterArray['=USER_ID'] = $this->getUserId();

        $rsBasketItem = Entity\BasketItemTable::getList(array(
            'filter' => $filterArray,
            'limit' => 1,
        ));

        $item = $rsBasketItem->fetch();

        if ($item) {
            $item = BasketBuilder::createItem($item);
        }

        return $item;
    }

    public function clear()
    {
        $userId = $this->getUserId();
        $result = Entity\BasketItemTable::deleteForUser($userId);
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
        $basketItem = Entity\BasketItemTable::getRowById($id);

        return $basketItem ? true : false;
    }

    public function prepareItem(Interfaces\BasketItemInterface $item)
    {
        $itemArray = $item->toArray();
        $itemArray['USER_ID'] = $this->getUserId();
        unset($itemArray['FULL_PRICE']);
        unset($itemArray['DISCOUNT_PRICE']);

        return $itemArray;
    }
}
