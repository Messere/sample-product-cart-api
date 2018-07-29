<?php

namespace Messere\Cart\Domain\Product\Product;

use Messere\Cart\Domain\Price\PriceBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use Ramsey\Uuid\UuidInterface;

class ProductBuilder
{
    private $productValidator;
    private $priceBuilder;

    public function __construct(ProductValidator $productValidator, PriceBuilder $priceBuilder)
    {
        $this->productValidator = $productValidator;
        $this->priceBuilder = $priceBuilder;
    }

    /**
     * @param UuidInterface $productId
     * @param string $name
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $currencySymbol
     * @return Product
     * @throws ProductValidationException
     */
    public function build(
        UuidInterface $productId,
        string $name,
        int $priceAmount,
        int $priceDivisor,
        string $currencySymbol
    ): Product {

        try {
            $price = $this->priceBuilder->buildPrice($priceAmount, $priceDivisor, $currencySymbol);
        } catch (PriceValidationException $e) {
            throw new ProductException('Invalid price ' . $e->getMessage(), 0, $e);
        }

        $product = new Product($productId, $name, $price);

        $this->productValidator->validate($product);

        return $product;
    }
}
