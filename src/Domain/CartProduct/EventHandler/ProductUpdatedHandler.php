<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\CartProduct\Repository\ProductCopier;
use Messere\Cart\Domain\Product\Event\ProductUpdatedEvent;

class ProductUpdatedHandler
{
    private $productCopier;

    public function __construct(ProductCopier $productCopier)
    {
        $this->productCopier = $productCopier;
    }

    public function handle(ProductUpdatedEvent $event): void
    {
        $this->productCopier->copy($event->getProductId());
    }
}
