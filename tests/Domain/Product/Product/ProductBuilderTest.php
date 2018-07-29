<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use Messere\Cart\Domain\Price\PriceBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class ProductBuilderTest extends TestCase
{
    private $validator;
    private $priceBuilder;
    private $builder;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = $this->prophesize(ProductValidator::class);
        $this->priceBuilder = $this->prophesize(PriceBuilder::class);

        $this->builder = new ProductBuilder(
            $this->validator->reveal(),
            $this->priceBuilder->reveal()
        );
    }

    /**
     * @expectedException \Messere\Cart\Domain\Product\Product\ProductException
     * @throws PriceValidationException
     * @throws \Exception
     */
    public function testShouldThrowExceptionOnPriceValidationFailure(): void
    {
        $this->priceBuilder->buildPrice(
            10,
            10,
            'PLN'
        )->willThrow(new PriceValidationException());

        $this->builder->build(
            Uuid::uuid4(),
            'zzz',
            10,
            10,
            'PLN'
        );
    }

    /**
     * @throws PriceValidationException
     * @throws \Exception
     */
    public function testShouldBuildProduct(): void
    {
        $this->priceBuilder->buildPrice(
            10,
            100,
            'PLN'
        )->willReturn(
            new Price(10, Currency::PLN(), 100)
        );

        /** @noinspection PhpParamsInspection */
        $this->validator->validate(Argument::any())->shouldBeCalled();

        $productId = Uuid::uuid4();
        $product = $this->builder->build(
            $productId,
            'zzz',
            10,
            100,
            'PLN'
        );

        $this->assertEquals(
            new Product($productId, 'zzz', new Price(10, Currency::PLN(), 100)),
            $product
        );
    }
}
