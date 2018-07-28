<?php

namespace Messere\Cart\Domain\Product\Command;

use Ramsey\Uuid\UuidInterface;

class UpdateProductCommand
{
    private $productId;
    private $name;
    private $priceAmount;
    private $priceDivisor;
    private $priceCurrency;

    public function __construct(
        UuidInterface $productId,
        ?string $name = null,
        ?int $priceAmount = null,
        ?int $priceDivisor = null,
        ?string $priceCurrency = null
    ) {
        $this->productId = $productId;
        $this->name = $name;
        $this->priceAmount = $priceAmount;
        $this->priceDivisor = $priceDivisor;
        $this->priceCurrency = $priceCurrency;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPriceAmount(): ?int
    {
        return $this->priceAmount;
    }

    public function getPriceDivisor(): ?int
    {
        return $this->priceDivisor;
    }

    public function getPriceCurrency(): ?string
    {
        return $this->priceCurrency;
    }
}
