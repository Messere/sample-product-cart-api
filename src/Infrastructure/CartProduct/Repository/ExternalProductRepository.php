<?php

namespace Messere\Cart\Infrastructure\CartProduct\Repository;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Messere\Cart\Domain\CartProduct\Repository\IProductRepository;
use Messere\Cart\Domain\Product\Repository\IProductRepository as IExternalProductRepository;
use Ramsey\Uuid\UuidInterface;

/**
 * in real case scenario this would call product endpoint to get product details,
 * for now we're directly asking product infrastructure for data
 */
class ExternalProductRepository implements IProductRepository
{
    private $productRepository;

    public function __construct(IExternalProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getById(UuidInterface $productId): ?CartProduct
    {
        $product = $this->productRepository->getById($productId);
        if (null !== $product) {
            return new CartProduct(
                $product->getId(),
                $product->getName(),
                $product->getPrice()
            );
        }
        return null;
    }
}
