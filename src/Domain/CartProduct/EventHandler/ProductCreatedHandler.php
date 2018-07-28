<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\CartProduct\Repository\ProductCopier;
use Messere\Cart\Domain\Product\Event\ProductCreatedEvent;

class ProductCreatedHandler
{
    private $productCopier;

    public function __construct(ProductCopier $productCopier)
    {
        $this->productCopier = $productCopier;
    }

    public function handle(ProductCreatedEvent $event): void
    {
        $this->productCopier->copy($event->getProductId());
    }
}
