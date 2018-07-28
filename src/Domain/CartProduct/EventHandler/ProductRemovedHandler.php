<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\CartProduct\Repository\ICartProductRepository;
use Messere\Cart\Domain\CartProduct\Repository\ProductCopier;
use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;

class ProductRemovedHandler
{
    private $cartProductRepository;

    public function __construct(ICartProductRepository $cartProductRepository)
    {
        $this->cartProductRepository = $cartProductRepository;
    }

    public function handle(ProductRemovedEvent $event): void
    {
        $this->cartProductRepository->remove($event->getProductId());
    }
}
