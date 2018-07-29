<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Price\PriceBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use Messere\Cart\Domain\Product\Event\ProductUpdatedEvent;
use Messere\Cart\Domain\Product\Product\ProductException;
use Messere\Cart\Domain\Product\Product\ProductValidationException;
use Messere\Cart\Domain\Product\Product\ProductValidator;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use SimpleBus\Message\Bus\MessageBus;

class UpdateProductHandler
{
    public const PRODUCT_DOES_NOT_EXIST = 404;

    private $productRepository;
    private $priceBuilder;
    private $productValidator;
    private $eventBus;

    public function __construct(
        IProductRepository $productRepository,
        PriceBuilder $priceBuilder,
        ProductValidator $productValidator,
        MessageBus $eventBus
    ) {
        $this->productRepository = $productRepository;
        $this->priceBuilder = $priceBuilder;
        $this->productValidator = $productValidator;
        $this->eventBus = $eventBus;
    }

    /**
     * @param UpdateProductCommand $command
     * @throws ProductException
     */
    public function handle(UpdateProductCommand $command): void
    {
        $product = $this->productRepository->getById(
            $command->getProductId()
        );

        if (null === $product) {
            throw new ProductException('Product does not exist', static::PRODUCT_DOES_NOT_EXIST);
        }

        $updatedProduct = $product;

        if ($command->getName() !== null) {
            /** @noinspection NullPointerExceptionInspection */
            $updatedProduct = $updatedProduct->withName($command->getName());
        }

        if ($command->getPriceAmount() !== null
            && $command->getPriceDivisor() !== null
            && $command->getPriceCurrency() !== null) {
            try {
                $updatedProduct = $updatedProduct->withPrice(
                    $this->priceBuilder->buildPrice(
                        $command->getPriceAmount(),
                        $command->getPriceDivisor(),
                        $command->getPriceCurrency()
                    )
                );
            } catch (PriceValidationException $e) {
                throw new ProductException('Updated price would be invalid: ' . $e->getMessage(), 0, $e);
            }
        }

        if ($updatedProduct !== $product) {
            try {
                $this->productValidator->validate($updatedProduct);
            } catch (ProductValidationException $e) {
                throw new ProductException('Updated product would be invalid: ' . $e->getMessage(), 0, $e);
            }
            $this->productRepository->save($updatedProduct);
            $this->eventBus->handle(new ProductUpdatedEvent($product->getProductId()));
        }
    }
}
