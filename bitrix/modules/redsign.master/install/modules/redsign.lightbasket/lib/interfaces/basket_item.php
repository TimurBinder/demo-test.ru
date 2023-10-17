<?php

namespace Redsign\LightBasket\Interfaces;

interface BasketItemInterface
{
    public function getId();
    public function getElementId();
    public function getPrice();
    public function getQuantity();
    public function getCurrency();
    public function getDiscount();
    public function getFullPrice();
    public function setId($id);
    public function setElementId($elementId);
    public function setPrice($price);
    public function setQuantity($quantity);
    public function setCurrency($currency);
    public function setDiscount($discount);
    public function computeFullPrice();
    public function incrementQuantity($times);
    public function decrementQuantity($times);
    public function compare(BasketItemInterface $item);
}
