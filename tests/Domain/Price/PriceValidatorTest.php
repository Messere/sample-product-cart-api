<?php

namespace Messere\Cart\Domain\Price;

use PHPUnit\Framework\TestCase;

class PriceValidatorTest extends TestCase
{
    /**
     * @expectedException \Messere\Cart\Domain\Price\PriceValidationException
     */
    public function testShouldNotAllowDivisorsThatAreNotPowerOf10(): void
    {
        $validator = new PriceValidator();
        $validator->validate(
            new Price(1, Currency::PLN(), 11)
        );
    }

    /**
     * @expectedException \Messere\Cart\Domain\Price\PriceValidationException
     */
    public function testShouldNotAllowZeroDivisor(): void
    {
        $validator = new PriceValidator();
        $validator->validate(
            new Price(1, Currency::PLN(), 0)
        );
    }

    /**
     * @dataProvider divisorProvider
     * @param int $divisor
     * @throws PriceValidationException
     */
    public function testShouldPassValidDivisor(int $divisor): void
    {
        $validator = new PriceValidator();
        $validator->validate(
            new Price(1, Currency::PLN(), $divisor)
        );
        $this->assertTrue(true);
    }

    public function divisorProvider(): array
    {
        return [
            [1],
            [10],
            [100],
            [1000],
            [10000],
        ];
    }
}
