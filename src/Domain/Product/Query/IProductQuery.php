<?php

namespace Messere\Cart\Domain\Product\Query;

use Messere\Cart\Domain\Product\Product\Product;

interface IProductQuery
{
    /**
     * Get products ordered by name starting from offset and at most limit pieces
     * @param int $offset
     * @param int $limit
     * @return Product[]
     */
    public function getProducts(int $offset, int $limit): array;
}
