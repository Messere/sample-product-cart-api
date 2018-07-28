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

    static function getZeroPrice(Currency $currency, int $divisor = 100): Price
    {
        return new Price(0, $currency, $divisor);
    }

    public function add(Price $price)
    {
        if (!$this->getCurrency()->isEqual($price->getCurrency())) {
            throw new PriceException('Cannot add prices with different currencies');
        }

        if ($price->getDivisor() > $this->getDivisor()) {
            $newDivisor = $price->getDivisor();
            $newAmount = $price->getAmount() + $this->withDivisor($newDivisor)->getAmount();
        } elseif ($price->getDivisor() < $this->getDivisor()) {
            $newDivisor = $this->getDivisor();
            $newAmount = $this->getAmount() + $price->withDivisor($newDivisor)->getAmount();
        } else {
            $newDivisor = $price->getDivisor();
            $newAmount = $price->getAmount() + $this->getAmount();
        }

        return new Price($newAmount, $price->getCurrency(), $newDivisor);
    }

    public function withDivisor(int $newDivisor): Price
    {
        if ($newDivisor < $this->getDivisor()) {
            throw new PriceException('Cannot decrease divisor');
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
