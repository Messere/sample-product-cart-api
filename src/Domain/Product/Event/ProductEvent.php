<?php

namespace Messere\Cart\Domain\Product\Event;

use Ramsey\Uuid\UuidInterface;

abstract class ProductEvent
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