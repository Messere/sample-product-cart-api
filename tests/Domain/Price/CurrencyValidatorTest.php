<?php

namespace Messere\Cart\Domain\Price;

use PHPUnit\Framework\TestCase;

class CurrencyValidatorTest extends TestCase
{
    /**
     * @expectedException \Messere\Cart\Domain\Price\PriceValidationException
     */
    public function testShouldThrowExceptionWithInvalidCurrency(): void
    {
        $validator = new CurrencyValidator();
        $validator->validate('IJK');
    }

    /**
     * @throws PriceValidationException
     */
    public function testShouldPassValidCurrency(): void
    {
        $validator = new CurrencyValidator();
        $validator->validate('PLN');
        $this->assertTrue(true);
    }
}
