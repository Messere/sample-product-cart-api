<?php

namespace Messere\Cart\Product;

use Messere\Cart\Price\Price;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AddProductCommand
{
    /**
     * @var UuidInterface
     */
    private $id;

    /**
     * @var Price
     */
    private $price;

    /**
     * @var string
     */
    private $name;

    /**
     * @param string $name
     * @param Price $price
     */
    public function __construct(string $name, Price $price)
    {
        try {
            $this->id = Uuid::uuid4();
        } catch (\Exception $e) {
            throw new ProductException('Failed to generate new product ID', $e);
        }
        $this->price = $price;
        $this->name = $name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    public function getName(): string
    {
        return $this->name;
    }
}