<?php


namespace Messere\Cart\Domain\Price;


class CurrencyValidator
{
    /**
     * @param string $currencySymbol
     * @throws PriceValidationException
     */
    public function validate(string $currencySymbol): void
    {
        if (!Currency::isValidValue($currencySymbol)) {
            throw new PriceValidationException('Invalid or unsupported currency');
        }
    }
}
