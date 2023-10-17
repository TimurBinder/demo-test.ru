<?php

namespace Redsign\LightBasket\Interfaces;

interface BasketProviderInterface
{
    public function getItems();
    public function getItemById($id);
    public function saveCollection(BasketCollectionInterface $items);
    public function add(BasketItemInterface $item);
    public function update(BasketItemInterface $item);
    public function removeById($id);
    public function findItem(array $options);
    public function getExistingItem(BasketItemInterface $item);
    public function isItemExist($id);
}
