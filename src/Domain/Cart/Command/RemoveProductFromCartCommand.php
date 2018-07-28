<?php

namespace Messere\Cart\Domain\Cart\Command;

use Ramsey\Uuid\UuidInterface;

class RemoveProductFromCartCommand
{
    private $cartId;
    private $productId;

    public function __construct(UuidInterface $cartId, UuidInterface $productId)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
    }

    public function getCartId(): UuidInterface
    {
        return $this->cartId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }
}
