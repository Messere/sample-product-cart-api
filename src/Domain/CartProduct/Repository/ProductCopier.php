<?php

namespace Messere\Cart\Domain\CartProduct\Repository;

use Ramsey\Uuid\UuidInterface;

class ProductCopier
{
    private $cartProductRepository;
    private $productRepository;

    /**
     * ProductCopier constructor.
     * @param ICartProductRepository $cartProductRepository
     * @param IProductRepository $productRepository
     * @SuppressWarnings(PHPMD.LongVariable)
     */
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
