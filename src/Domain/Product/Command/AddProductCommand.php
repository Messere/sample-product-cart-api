<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Product\ProductException;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;

class AddProductCommand
{
    private $productId;
    private $name;
    private $priceAmount;
    private $priceDivisor;
    private $priceCurrency;

    /**
     * @param string $name
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $priceCurrency
     * @param UuidFactoryInterface $uuidFactory
     */
    public function __construct(
        string $name,
        int $priceAmount,
        int $priceDivisor,
        string $priceCurrency,
        UuidFactoryInterface $uuidFactory
    ) {
        try {
            $this->productId = $uuidFactory->uuid4();
        } catch (\Exception $e) {
            throw new ProductException('Failed to generate new product ID: ' . $e->getMessage(), 0, $e);
        }
        $this->name = $name;
        $this->priceAmount = $priceAmount;
        $this->priceDivisor = $priceDivisor;
        $this->priceCurrency = $priceCurrency;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
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
