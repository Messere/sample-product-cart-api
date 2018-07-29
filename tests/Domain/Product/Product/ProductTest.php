<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductTest extends TestCase
{
    private const ID = 'id';
    private const NAME = 'name';
    private const PRICE = 'price';
    private const AMOUNT = 'amount';
    private const DIVISOR = 'divisor';
    private const CURRENCY = 'currency';

    /**
     * @throws \Exception
     */
    public function testProductSerialization(): void
    {
        $productId = Uuid::uuid4();
        $product = new Product(
            $productId,
            'test',
            new Price(100, Currency::EUR(), 10)
        );

        $this->assertEquals([
            self::ID => $productId->toString(),
            self::NAME => 'test',
            self::PRICE => [
                self::AMOUNT => 100,
                self::DIVISOR => 10,
                self::CURRENCY => 'EUR',
            ]
        ], $product->jsonSerialize());
    }

    /**
     * @throws \Exception
     */
    public function testPriceChange(): void
    {
        $productId = Uuid::uuid4();
        $product = new Product(
            $productId,
            'test',
            new Price(100, Currency::EUR(), 10)
        );

        $this->assertEquals([
            self::ID => $productId->toString(),
            self::NAME => 'test',
            self::PRICE => [
                self::AMOUNT => 200,
                self::DIVISOR => 100,
                self::CURRENCY => 'PLN',
            ]
        ], $product->withPrice(
            new Price(200, Currency::PLN(), 100)
        )->jsonSerialize());
    }

    /**
     * @throws \Exception
     */
    public function testNameChange(): void
    {
        $productId = Uuid::uuid4();
        $product = new Product(
            $productId,
            'test',
            new Price(100, Currency::EUR(), 10)
        );

        $this->assertEquals([
            self::ID => $productId->toString(),
            self::NAME => 'changed',
            self::PRICE => [
                self::AMOUNT => 100,
                self::DIVISOR => 10,
                self::CURRENCY => 'EUR',
            ]
        ], $product->withName('changed')->jsonSerialize());
    }
}
