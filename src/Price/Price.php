<?php

namespace Messere\Cart\Price;

class Price
{
    private $amount;
    private $divisor;
    private $currency;

    /**
     * Price constructor.
     * @param $amount
     * @param $divisor
     * @param $currency
     */
    public function __construct(int $amount, Currency $currency, int $divisor = 100)
    {
        $this->amount = $amount;
        $this->divisor = $divisor;
        $this->currency = $currency;
    }


}