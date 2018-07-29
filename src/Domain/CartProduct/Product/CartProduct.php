<?php

namespace Messere\Cart\Domain\CartProduct\Product;

use Messere\Cart\Domain\Price\Price;
use Ramsey\Uuid\UuidInterface;

class CartProduct implements \JsonSerializable
{
    private $cartProductId;
    private $name;
    private $price;
    private $amount;

    public function __construct(UuidInterface $cartProductId, string $name, Price $price, int $amount = 1)
    {
        $this->cartProductId = $cartProductId;
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
    }

    public function getCartProductId(): UuidInterface
    {
        return $this->cartProductId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getCartProductId()->toString(),
            'name' => $this->getName(),
            'unitPrice' => $this->getPrice()->jsonSerialize(),
            'amount' => $this->getAmount(),
        ];
    }
}
