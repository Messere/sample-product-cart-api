<?php

namespace Messere\Cart\Domain\CartProduct\Repository;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Ramsey\Uuid\UuidInterface;

interface IProductRepository
{
    public function getById(UuidInterface $productId): ?CartProduct;
}
