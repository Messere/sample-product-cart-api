<?php

namespace Messere\Cart\Domain\Price;

class PriceValidator
{
    /**
     * @param Price $price
     * @throws PriceValidationException
     */
    public function validate(Price $price): void
    {
        $divisor = $price->getDivisor();

        if ($divisor === 0) {
            throw new PriceValidationException('Divisor must be greater than zero');
        }

        $divisorPower = log10($divisor);

        /** @noinspection TypeUnsafeComparisonInspection */
        if ($divisorPower != (int)$divisorPower) {
            throw new PriceValidationException('Divisor must be an integral power of ten (1, 10, 100, ...)');
        }
    }
}
