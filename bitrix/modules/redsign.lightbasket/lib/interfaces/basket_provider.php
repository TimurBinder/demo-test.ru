<?php

namespace Redsign\LightBasket\Interfaces;

use Redsign\LightBasket\BasketCollection;

interface BasketProviderInterface
{
    public function getItems(): BasketCollectionInterface;
    public function getItemById(int $id): ?BasketItemInterface;
    public function saveCollection(BasketCollectionInterface $items): void;
    public function add(BasketItemInterface $item): bool;
    public function update(BasketItemInterface $item): bool;
    public function removeById(int $id): bool;
    public function findItem(array $options): ?BasketItemInterface;
    public function getExistingItem(BasketItemInterface $item): ?BasketItemInterface;
    public function isItemExist(int $id): bool;
    public function clear(): void;
}
