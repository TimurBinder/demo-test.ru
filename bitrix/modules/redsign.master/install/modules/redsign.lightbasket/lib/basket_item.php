<?php

namespace Redsign\LightBasket;

class BasketItem implements Interfaces\Arrayble, Interfaces\BasketItemInterface
{
    private $id;
    private $elementId;
    private $price;
    private $currency;
    private $quantity;
    private $discount;
    private $fullprice;

    public function __construct(
        $id = 0,
        $elementId = 0,
        $price = 0,
        $currency = '',
        $discount = 0,
        $quantity = 1
    ) {
        $this->setId($id);
        $this->setElementId($elementId);
        $this->setPrice($price);
        $this->setCurrency($currency);
        $this->setDiscount($discount);
        $this->setQuantity($quantity);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setElementId($elementId)
    {
        $this->elementId = $elementId;
    }

    public function getElementId()
    {
        return $this->elementId;
    }

    public function setPrice($price)
    {
        $this->price = (float) $price;
        $this->computeFullPrice();
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = (float) $quantity;
        $this->computeFullPrice();
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setDiscount($discount)
    {
        $this->discount = (float) $discount;
        $this->computeFullPrice();
    }

    public function getDiscount()
    {
        return $this->discount;
    }

    public function incrementQuantity($times = 1)
    {
        $this->quantity += $times;
        $this->computeFullPrice();
    }

    public function decrementQuantity($times = 1)
    {
        $this->quantity -= $times;
        $this->computeFullPrice();
    }

    public function computeFullPrice()
    {
        $this->fullprice = $this->getDiscountPrice() * $this->getQuantity();

        if ($this->fullprice < 0) {
            $this->fullprice = 0;
        }
    }

    public function getDiscountPrice()
    {
        return $this->getPrice() - $this->getDiscount();
    }

    public function getFullPrice()
    {
        return $this->fullprice;
    }

    public function compare(Interfaces\BasketItemInterface $item)
    {
        return
            $this->getElementId() == $item->getElementId() &&
            $this->getPrice() == $item->getPrice() &&
            $this->getDiscount() == $item->getDiscount() &&
            $this->getCurrency() == $item->getCurrency();
    }

    public function toArray()
    {
        return array(
            'ID' => $this->getId(),
            'ELEMENT_ID' => $this->getElementId(),
            'PRICE' => $this->getPrice(),
            'DISCOUNT_PRICE' => $this->getDiscountPrice(),
            'FULL_PRICE' => $this->getFullPrice(),
            'CURRENCY' => $this->getCurrency(),
            'DISCOUNT' => $this->getDiscount(),
            'QUANTITY' => $this->getQuantity(),
         );
    }
}
