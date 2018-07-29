<?php

namespace Messere\Cart\Domain\CartProduct\Repository;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Ramsey\Uuid\UuidInterface;

interface ICartProductRepository
{
    public function remove(UuidInterface $productId): void;
    public function save(CartProduct $product): void;
}
