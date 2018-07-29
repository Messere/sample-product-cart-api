<?php

namespace Messere\Cart\Domain\Price;

class PriceBuilder
{
    private $priceValidator;
    private $currencyValidator;

    public function __construct(PriceValidator $priceValidator, CurrencyValidator $currencyValidator)
    {
        $this->priceValidator = $priceValidator;
        $this->currencyValidator = $currencyValidator;
    }

    /**
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $currencySymbol
     * @return Price
     * @throws PriceValidationException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function buildPrice(int $priceAmount, int $priceDivisor, string $currencySymbol): Price
    {
        $this->currencyValidator->validate($currencySymbol);

        /** @noinspection PhpUnhandledExceptionInspection */
        $price = new Price(
            $priceAmount,
            Currency::createFromConstantName($currencySymbol),
            $priceDivisor
        );

        $this->priceValidator->validate($price);

        return $price;
    }
}
