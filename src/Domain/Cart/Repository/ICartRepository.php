<?php

namespace Messere\Cart\Domain\Cart\Repository;

use Ramsey\Uuid\UuidInterface;

interface ICartRepository
{
    public function increaseProductCountInCart(UuidInterface $cartId, UuidInterface $productId): void;
    public function decreaseProductCountInCart(UuidInterface $cartId, UuidInterface $productId): void;
    public function getTotalAmount(UuidInterface $cartId): int;
}
