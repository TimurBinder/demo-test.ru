<?php

namespace Redsign\LightBasket;

class BasketItem implements Interfaces\BasketItemInterface
{
    private int $id;
    private int $elementId;
    private float $price;
    private string $currency;
    private float $quantity;
    private float $discount = 0;
    private float $fullprice;

    public function __construct(
        int $id = 0,
        int $elementId = 0,
        float $price = 0,
        string $currency = '',
        float $discount = 0,
        float $quantity = 1
    ) {
        $this->id = $id;
        $this->elementId = $elementId;
        $this->discount = $discount;
        $this->quantity = $quantity;
        $this->currency = $currency;
        $this->price = $price;

        $this->computeFullPrice();
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setElementId(int $elementId): self
    {
        $this->elementId = $elementId;

        return $this;
    }

    public function getElementId(): int
    {
        return $this->elementId;
    }

    public function setPrice(float $price): self
    {
        $this->price = (float) $price;
        $this->computeFullPrice();

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setQuantity(float $quantity): self
    {
        $this->quantity = (float) $quantity;
        $this->computeFullPrice();

        return $this;
    }

    public function getQuantity(): float
    {
        return $this->quantity;
    }

    public function setDiscount(float $discount): self
    {
        $this->discount = (float) $discount;
        $this->computeFullPrice();

        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function incrementQuantity(float $times = 1): void
    {
        $this->quantity += $times;
        $this->computeFullPrice();
    }

    public function decrementQuantity(float $times = 1): void
    {
        $this->quantity -= $times;
        $this->computeFullPrice();
    }

    public function computeFullPrice(): void
    {
        $this->fullprice = $this->getDiscountPrice() * $this->getQuantity();

        if ($this->fullprice < 0) {
            $this->fullprice = 0;
        }
    }

    public function getDiscountPrice(): float
    {
        return $this->getPrice() - $this->getDiscount();
    }

    public function getFullPrice(): float
    {
        return $this->fullprice;
    }

    public function compare(Interfaces\BasketItemInterface $item): bool
    {
        return
            $this->getElementId() == $item->getElementId() &&
            $this->getPrice() == $item->getPrice() &&
            $this->getDiscount() == $item->getDiscount() &&
            $this->getCurrency() == $item->getCurrency();
    }

    public function toArray(): array
    {
        return [
            'ID' => $this->getId(),
            'ELEMENT_ID' => $this->getElementId(),
            'PRICE' => $this->getPrice(),
            'DISCOUNT_PRICE' => $this->getDiscountPrice(),
            'FULL_PRICE' => $this->getFullPrice(),
            'CURRENCY' => $this->getCurrency(),
            'DISCOUNT' => $this->getDiscount(),
            'QUANTITY' => $this->getQuantity(),
        ];
    }
}
