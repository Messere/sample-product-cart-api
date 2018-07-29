<?php

namespace Messere\Cart\Domain\Price;

class Price implements \JsonSerializable
{
    private $amount;
    private $divisor;
    private $currency;

    public function __construct(int $amount, Currency $currency, int $divisor = 100)
    {
        $this->amount = $amount;
        $this->divisor = $divisor;
        $this->currency = $currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getDivisor(): int
    {
        return $this->divisor;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function jsonSerialize()
    {
        return [
            'amount' => $this->getAmount(),
            'divisor' => $this->getDivisor(),
            'currency' => $this->getCurrency()->getName()
        ];
    }

    public function add(Price $price): Price
    {
        if (!$this->getCurrency()->isEqual($price->getCurrency())) {
            throw new PriceException('Cannot add prices with different currencies');
        }

        if ($price->getAmount() === 0) {
            return $this;
        }

        $newDivisor = max($price->getDivisor(), $this->getDivisor());
        $newAmount = $this->withDivisor($newDivisor)->getAmount() + $price->withDivisor($newDivisor)->getAmount();

        return new Price($newAmount, $price->getCurrency(), $newDivisor);
    }

    public function withDivisor(int $newDivisor): Price
    {
        if ($newDivisor < $this->getDivisor()) {
            throw new PriceException('Cannot decrease divisor');
        }

        if ($newDivisor === $this->getDivisor()) {
            return $this;
        }

        $newAmount = $this->getAmount() * $newDivisor / $this->getDivisor();
        return new Price($newAmount, $this->getCurrency(), $newDivisor);
    }

    public function multipliedBy(int $multiplier): Price
    {
        return 1 === $multiplier
            ? $this
            : new Price($multiplier * $this->getAmount(), $this->getCurrency(), $this->getDivisor());
    }
}
