<?php

namespace Redsign\LightBasket;

use Bitrix\Main\Application;
use Bitrix\Main\Web\Cookie;
use Redsign\LightBasket\Interfaces\BasketItemInterface;

class BasketDatabaseProvider implements Interfaces\BasketProviderInterface
{
    private int $userId = 0;
    private string $cookieKey;

    public function __construct()
    {
        $this->cookieKey = 'REDSIGN_LIGHTBASKET_USER_CODE_' . SITE_ID;
    }

    public function getUserId(): int
    {
        if ($this->userId !== 0) {
            return $this->userId;
        }

        /** @var \Bitrix\Main\HttpRequest */
        $request = Application::getInstance()->getContext()->getRequest();

        $userCode = $request->getCookie($this->cookieKey);

        if (is_null($userCode)) {
            $userCode = md5(time() . \Bitrix\Main\Security\Random::getString(10, true));
            $cookie = new Cookie($this->cookieKey, $userCode);

            /** @var \Bitrix\Main\HttpResponse */
            $response = Application::getInstance()->getContext()->getResponse();
            $response->addCookie($cookie);

            $result = Entity\BasketUserTable::add([
                'CODE' => $userCode,
            ]);

            if (!$result->isSuccess()) {
                return 0;
            }
        }

        $basketUser = Entity\BasketUserTable::getRowByCode($userCode);
        if ($basketUser) {
            $this->userId = $basketUser['ID'];
        } else {
            $result = Entity\BasketUserTable::add([
                'CODE' => $userCode,
            ]);

            $this->userId = (int) $result->getId();
        }

        return $this->userId;
    }

    public function getItems(): BasketCollection
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

    public function getItemById(int $id): ?BasketItemInterface
    {
        $item = Entity\BasketItemTable::getRowById($id);

        if ($item) {
            return BasketBuilder::createItem($item);
        }

        return null;
    }

    public function saveCollection(Interfaces\BasketCollectionInterface $items): void
    {
        foreach ($items as $item) {
            $itemId = $item->getId();

            if ($this->isItemExist($itemId)) {
                $this->update($item);
            } else {
                $this->add($item);
            }
        }
    }

    public function add(Interfaces\BasketItemInterface $item): bool
    {
        $itemArray = $this->prepareItem($item);

        if (!$itemArray) {
            return false;
        }

        $result = Entity\BasketItemTable::add($itemArray);

        return $result->isSuccess();
    }

    public function update(Interfaces\BasketItemInterface $item): bool
    {
        $itemId = $item->getId();

        if (empty($itemId)) {
            return false;
        }

        // $userId = $this->getUserId();
        $itemArray = $this->prepareItem($item);

        $result = Entity\BasketItemTable::update($itemId, $itemArray);

        return $result->isSuccess();
    }

    public function removeById(int $id): bool
    {
        $result = Entity\BasketItemTable::delete($id);

        return $result->isSuccess();
    }

    public function findItem(array $options): ?BasketItemInterface
    {
        $filterArray = [];
        foreach ($options as $key => $option) {
            $filterArray['=' . $key][] = $option;
        }
        $filterArray['=USER_ID'] = $this->getUserId();

        $rsBasketItem = Entity\BasketItemTable::getList([
            'filter' => $filterArray,
            'limit' => 1,
        ]);

        $item = null;
        if ($arItem = $rsBasketItem->fetch()) {
            $item = BasketBuilder::createItem($arItem);
        }

        return $item;
    }

    public function clear(): void
    {
        $userId = $this->getUserId();
        Entity\BasketItemTable::deleteForUser($userId);
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
        $basketItem = Entity\BasketItemTable::getRowById($id);

        return $basketItem ? true : false;
    }

    public function prepareItem(Interfaces\BasketItemInterface $item): array
    {
        $itemArray = $item->toArray();
        $itemArray['USER_ID'] = $this->getUserId();
        unset($itemArray['FULL_PRICE']);
        unset($itemArray['DISCOUNT_PRICE']);

        return $itemArray;
    }
}
