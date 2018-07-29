<?php

namespace Messere\Cart\Domain\Price;

use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testShouldSerializePrice(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $this->assertEquals([
            'amount' => 1000,
            'divisor' => 100,
            'currency' => 'PLN',
        ], $price->jsonSerialize());
    }

    public function testShouldAddPrices(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $price = $price->add(new Price(1000, Currency::PLN(), 1000));

        $this->assertEquals(
            new Price(11000, Currency::PLN(), 1000),
            $price
        );
    }

    /**
     * @expectedException \Messere\Cart\Domain\Price\PriceException
     */
    public function testShouldNotAllowToAddPricesWithDifferentCurrencies(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $price->add(new Price(1000, Currency::EUR(), 1000));
    }

    public function testShouldChangePriceDivisor(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $this->assertEquals(
            new Price(10000, Currency::PLN(), 1000),
            $price->withDivisor(1000)
        );
    }

    /**
     * @expectedException \Messere\Cart\Domain\Price\PriceException
     */
    public function testShouldForbidToChangeToSmallerDivisor(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $price->withDivisor(10);
    }

    public function testShouldMultiplyPriceByNumber(): void
    {
        $price = new Price(1000, Currency::PLN(), 100);
        $this->assertEquals(
            new Price(3000, Currency::PLN(), 100),
            $price->multipliedBy(3)
        );
    }
}
