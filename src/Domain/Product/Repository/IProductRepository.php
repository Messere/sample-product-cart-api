<?php

namespace Messere\Cart\Domain\Product\Repository;

use Messere\Cart\Domain\Product\Product\Product;
use Ramsey\Uuid\UuidInterface;

interface IProductRepository
{
    public function save(Product $product): void;

    public function remove(UuidInterface $productId);

    public function getById(UuidInterface $getProductId): ?Product;
}
