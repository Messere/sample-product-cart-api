<?php

namespace Messere\Cart\Domain\CartProduct\Repository;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class ProductCopierTest extends TestCase
{
    private $cartProductRepository;
    private $productRepository;
    private $copier;

    public function setUp(): void
    {
        parent::setUp();

        $this->cartProductRepository = $this->prophesize(ICartProductRepository::class);
        $this->productRepository = $this->prophesize(IProductRepository::class);
        $this->copier = new ProductCopier(
            $this->cartProductRepository->reveal(),
            $this->productRepository->reveal()
        );
    }

    /**
     * @throws \Exception
     */
    public function testShouldCopyProduct(): void
    {
        $productId = Uuid::uuid4();
        $product = $this->prophesize(CartProduct::class);
        $this->productRepository->getById($productId)->willReturn(
            $product->reveal()
        );
        $this->cartProductRepository->save($product)->shouldBeCalledTimes(1);
        $this->copier->copy($productId);
    }

    /**
     * @throws \Exception
     */
    public function testShouldNotCopyIfProductNotFound(): void
    {
        $productId = Uuid::uuid4();
        $this->productRepository->getById($productId)->willReturn(null);
        /** @noinspection PhpParamsInspection */
        $this->cartProductRepository->save(Argument::any())->shouldNotBeCalled();
        $this->copier->copy($productId);
    }
}
