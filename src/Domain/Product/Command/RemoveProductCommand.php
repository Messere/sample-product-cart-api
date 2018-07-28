<?php

namespace Messere\Cart\Domain\Product\Command;

use Ramsey\Uuid\UuidInterface;

class RemoveProductCommand
{
    private $productId;

    public function __construct(UuidInterface $productId)
    {
        $this->productId = $productId;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }
}
