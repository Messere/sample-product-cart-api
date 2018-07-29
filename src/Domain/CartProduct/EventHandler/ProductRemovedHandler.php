<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;
use Messere\Cart\Domain\CartProduct\Repository\ICartProductRepository;
use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;

class ProductRemovedHandler
{
    private $repository;
    private $cartRepository;

    public function __construct(ICartProductRepository $repository, ICartRepository $cartRepository)
    {
        $this->repository = $repository;
        $this->cartRepository = $cartRepository;
    }

    public function handle(ProductRemovedEvent $event): void
    {
        $productId = $event->getProductId();
        $this->cartRepository->removeProductFromCarts($productId);
        $this->repository->remove($productId);
    }
}
