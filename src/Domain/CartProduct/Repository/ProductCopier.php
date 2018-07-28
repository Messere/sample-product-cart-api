<?php

namespace Messere\Cart\Domain\CartProduct\Repository;

use Ramsey\Uuid\UuidInterface;

class ProductCopier
{
    private $cartProductRepository;
    private $productRepository;

    public function __construct(ICartProductRepository $cartProductRepository, IProductRepository $productRepository)
    {
        $this->cartProductRepository = $cartProductRepository;
        $this->productRepository = $productRepository;
    }

    public function copy(UuidInterface $productId): void
    {
        $product = $this->productRepository->getById($productId);
        if (null !== $product) {
            $this->cartProductRepository->save($product);
        }
    }
}
