<?php

namespace Messere\Cart\Domain\CartProduct\Product;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use Messere\Cart\Domain\Price\PriceBuilder;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartProductBuilderTest extends TestCase
{
    /**
     * @throws \Messere\Cart\Domain\Price\PriceValidationException
     * @throws \Exception
     */
    public function testShouldBuildCartProduct(): void
    {
        $priceBuilder = $this->prophesize(PriceBuilder::class);
        $price = new Price(100, Currency::PLN(), 10);
        $priceBuilder->buildPrice(100, 10, 'PLN')->willReturn(
            $price
        );
        $builder = new CartProductBuilder($priceBuilder->reveal());

        $productId = Uuid::uuid4();
        $product = $builder->build($productId, 'zzz', 100, 10, 'PLN', 2);

        $this->assertEquals(
            new CartProduct($productId, 'zzz', $price, 2),
            $product
        );
    }
}
