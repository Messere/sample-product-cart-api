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
        $divisorPower = log10($price->getDivisor());

        /** @noinspection TypeUnsafeComparisonInspection */
        if ($divisorPower != (int)$divisorPower) {
            throw new PriceValidationException('Divisor must be an integral power of ten (1, 10, 100, ...)');
        }
    }
}
