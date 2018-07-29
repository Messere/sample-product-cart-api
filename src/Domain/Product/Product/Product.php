<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\Price;
use Ramsey\Uuid\UuidInterface;

class Product implements \JsonSerializable
{
    private $productId;
    private $name;
    private $price;

    public function __construct(UuidInterface $productId, string $name, Price $price)
    {
        $this->productId = $productId;
        $this->name = $name;
        $this->price = $price;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
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
        return new Product($this->productId, $this->name, $price);
    }

    public function withName(string $name): Product
    {
        return new Product($this->productId, $name, $this->price);
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getProductId()->toString(),
            'name' => $this->getName(),
            'price' => $this->getPrice()->jsonSerialize(),
        ];
    }
}
