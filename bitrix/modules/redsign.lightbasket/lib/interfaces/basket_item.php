<?php

namespace Redsign\LightBasket\Interfaces;

interface BasketItemInterface extends Arrayble
{
    public function getId(): int;
    public function getElementId(): int;
    public function getPrice(): float;
    public function getQuantity(): float;
    public function getCurrency(): string;
    public function getDiscount(): float;
    public function getDiscountPrice(): float;
    public function getFullPrice(): float;
    public function setId(int $id): self;
    public function setElementId(int $elementId): self;
    public function setPrice(float $price): self;
    public function setQuantity(float $quantity): self;
    public function setCurrency(string $currency): self;
    public function setDiscount(float $discount): self;
    public function computeFullPrice(): void;
    public function incrementQuantity(float $times): void;
    public function decrementQuantity(float $times): void;
    public function compare(BasketItemInterface $item): bool;
    public function toArray(): array;
}
