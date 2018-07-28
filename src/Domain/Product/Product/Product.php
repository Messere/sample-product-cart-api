<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\Price;
use Ramsey\Uuid\UuidInterface;

class Product implements \JsonSerializable
{
    private $id;
    private $name;
    private $price;

    public function __construct(UuidInterface $id, string $name, Price $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function withPrice(Price $price): Product
    {
        return new Product($this->id, $this->name, $price);
    }

    public function withName(string $name): Product
    {
        return new Product($this->id, $name, $this->price);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId()->toString(),
            'name' => $this->getName(),
            'price' => $this->getPrice()->jsonSerialize(),
        ];
    }
}
