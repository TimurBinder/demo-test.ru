<?php

namespace Redsign\LightBasket\Interfaces;

/**
 * @extends \IteratorAggregate<string, BasketItemInterface>
 */
interface BasketCollectionInterface extends Arrayble, \IteratorAggregate, \Countable
{
    public function __construct(array $items);

    /**
     * @return \ArrayIterator<string, BasketItemInterface>
     */
    public function getIterator(): \ArrayIterator;

    public function count(): int;
    public function toArray(): array;
    public function isExists(string $key): bool;
    public function push(BasketItemInterface $item): void;
    public function remove(string $key): void;
    public function contains(BasketItemInterface $item): ?string;
}
