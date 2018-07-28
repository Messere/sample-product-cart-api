<?php

namespace Messere\Cart\Domain\Cart\Query;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Ramsey\Uuid\UuidInterface;

interface ICartQuery
{
    /**
     * @param UuidInterface $cartId
     * @return CartProduct[]
     */
    public function getProductsFromCart(UuidInterface $cartId): array;
}
