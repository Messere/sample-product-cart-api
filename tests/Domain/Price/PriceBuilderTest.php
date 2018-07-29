<?php

namespace Messere\Cart\Domain\Price;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class PriceBuilderTest extends TestCase
{
    /**
     * @throws PriceValidationException
     */
    public function testShouldBuildAPrice(): void
    {
        $priceValidator = $this->prophesize(PriceValidator::class);
        /** @noinspection PhpParamsInspection */
        $priceValidator->validate(Argument::any())->shouldBeCalled();
        $currencyValidator = $this->prophesize(CurrencyValidator::class);
        $currencyValidator->validate('PLN')->shouldBeCalled();

        $builder = new PriceBuilder(
            $priceValidator->reveal(),
            $currencyValidator->reveal()
        );

        $price = $builder->buildPrice(100, 10, 'PLN');

        $this->assertEquals(
            new Price(100, Currency::PLN(), 10),
            $price
        );
    }
}
