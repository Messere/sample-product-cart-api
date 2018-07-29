<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductValidatorTest extends TestCase
{
    /**
     * @throws \Exception
     * #@expectedException \Messere\Cart\Domain\Product\Product\ProductValidationException
     */
    public function testShouldThrowExceptionOnEmptyName(): void
    {
        $validator = new ProductValidator();
        $product = new Product(
            Uuid::uuid4(),
            '',
            new Price(10, Currency::PLN())
        );

        $validator->validate($product);
    }

    /**
     * @dataProvider invalidPriceProvider
     * @throws \Exception
     * #@expectedException \Messere\Cart\Domain\Product\Product\ProductValidationException
     */
    public function testShouldThrowExceptionOnPriceLowerThanOne(int $price): void
    {
        $validator = new ProductValidator();
        $product = new Product(
            Uuid::uuid4(),
            'zzz',
            new Price($price, Currency::PLN())
        );

        $validator->validate($product);
    }

    public function invalidPriceProvider(): array
    {
        return [
            [0],
            [-1]
        ];
    }

    /**
     * @throws \Exception
     */
    public function testShouldPassValidProduct(): void
    {
        $validator = new ProductValidator();
        $product = new Product(
            Uuid::uuid4(),
            'zzz',
            new Price(1, Currency::PLN())
        );

        $validator->validate($product);
        $this->assertTrue(true);
    }
}
