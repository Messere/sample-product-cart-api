<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\CartProduct\Repository\ICartProductRepository;
use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;

class ProductRemovedHandler
{
    private $repository;

    public function __construct(ICartProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(ProductRemovedEvent $event): void
    {
        $this->repository->remove($event->getProductId());
    }
}
