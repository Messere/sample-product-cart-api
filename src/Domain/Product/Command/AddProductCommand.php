<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Product\ProductException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AddProductCommand
{
    private $id;
    private $name;
    private $priceAmount;
    private $priceDivisor;
    private $priceCurrency;

    /**
     * @param string $name
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $priceCurrency
     */
    public function __construct(string $name, int $priceAmount, int $priceDivisor, string $priceCurrency)
    {
        try {
            $this->id = Uuid::uuid4();
        } catch (\Exception $e) {
            throw new ProductException('Failed to generate new product ID: ' . $e->getMessage(), 0, $e);
        }
        $this->name = $name;
        $this->priceAmount = $priceAmount;
        $this->priceDivisor = $priceDivisor;
        $this->priceCurrency = $priceCurrency;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriceAmount(): int
    {
        return $this->priceAmount;
    }

    public function getPriceDivisor(): int
    {
        return $this->priceDivisor;
    }

    public function getPriceCurrency(): string
    {
        return $this->priceCurrency;
    }
}
