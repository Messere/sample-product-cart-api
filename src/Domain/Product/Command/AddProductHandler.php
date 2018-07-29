<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Event\ProductCreatedEvent;
use Messere\Cart\Domain\Product\Product\ProductBuilder;
use Messere\Cart\Domain\Product\Product\ProductException;
use Messere\Cart\Domain\Product\Product\ProductValidationException;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use SimpleBus\Message\Bus\MessageBus;

class AddProductHandler
{
    private $productRepository;
    private $productBuilder;
    private $eventBus;

    public function __construct(
        IProductRepository $productRepository,
        ProductBuilder $productBuilder,
        MessageBus $eventBus
    ) {
        $this->productRepository = $productRepository;
        $this->productBuilder = $productBuilder;
        $this->eventBus = $eventBus;
    }


    /**
     * @param AddProductCommand $command
     * @throws ProductException
     */
    public function handle(AddProductCommand $command): void
    {
        try {
            $product = $this->productBuilder->build(
                $command->getProductId(),
                $command->getName(),
                $command->getPriceAmount(),
                $command->getPriceDivisor(),
                $command->getPriceCurrency()
            );
        } catch (ProductValidationException $e) {
            throw new ProductException($e->getMessage(), 0, $e);
        }

        $this->productRepository->save($product);
        $this->eventBus->handle(new ProductCreatedEvent($product->getProductId()));
    }
}
