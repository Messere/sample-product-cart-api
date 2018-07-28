<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use SimpleBus\Message\Bus\MessageBus;

class RemoveProductHandler
{
    private $productRepository;
    private $eventBus;

    public function __construct(
        IProductRepository $productRepository,
        MessageBus $eventBus
    ) {
        $this->productRepository = $productRepository;
        $this->eventBus = $eventBus;
    }

    public function handle(RemoveProductCommand $command): void
    {
        $productId = $command->getProductId();
        $this->productRepository->remove($productId);
        $this->eventBus->handle(new ProductRemovedEvent($productId));
    }
}
