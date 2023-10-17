<?php

namespace Redsign\LightBasket\Interfaces;

interface BasketCollectionInterface
{
    public function __construct($items);
    public function isExists($key);
    public function push(BasketItemInterface $item);
    public function remove($key);
    public function contains(BasketItemInterface $item);
}
