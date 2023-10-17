<?php

namespace Redsign\LightBasket;

use Redsign\LightBasket\Interfaces\BasketItemInterface;

class BasketBuilder
{
    public static function createCollection(): BasketCollection
    {
        return new BasketCollection();
    }

    public static function createItem(array $item): BasketItemInterface
    {
        $elementId = $item['ELEMENT_ID'];
        $price = $item['PRICE'];
        $currency = $item['CURRENCY'];

        $quantity = isset($item['QUANTITY']) ? $item['QUANTITY'] : 1;
        $discount = isset($item['DISCOUNT']) ? $item['DISCOUNT'] : 0;

        $id = isset($item['ID']) ? $item['ID'] : 0;

        $basketItem = new BasketItem($id, $elementId, $price, $currency, $discount, $quantity);

        return $basketItem;
    }
}
