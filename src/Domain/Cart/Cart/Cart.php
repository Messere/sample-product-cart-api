<?php

namespace Messere\Cart\Domain\Cart\Cart;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Messere\Cart\Domain\Price\Price;

class Cart implements \JsonSerializable
{
    /**
     * @var Price|null
     */
    private $totalPrice;
    /**
     * @var CartProduct[]
     */
    private $products;

    /**
     * Cart constructor.
     * @param CartProduct[] $products
     */
    public function __construct(array $products)
    {
        $this->products = $products;
        $this->totalPrice = $this->calculateTotal($products);
    }

    /**
     * @param CartProduct[] $products
     * @return Price|null
     */
    private function calculateTotal(array $products): ?Price
    {
        if (0 === \count($products)) {
            return null;
        }

        $priceSum = new Price(
            0,
            $products[0]->getPrice()->getCurrency(),
            $products[0]->getPrice()->getDivisor()
        );

        foreach ($products as $product) {
            $priceSum = $priceSum->add(
                $product->getPrice()->multipliedBy(
                    $product->getAmount()
                )
            );
        }

        return $priceSum;
    }

    /**
     * @return Price|null
     */
    public function getTotalPrice(): ?Price
    {
        return $this->totalPrice;
    }

    /**
     * @return null|string
     */
    public function getTotalPriceFormatted(): ?string
    {
        $price = $this->getTotalPrice();
        return null === $price ? null : sprintf(
            '%.' . log10($price->getDivisor()) . 'f %s',
            $price->getAmount() / $price->getDivisor(),
            $price->getCurrency()->getName()
        );
    }

    /**
     * @return CartProduct[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    public function jsonSerialize()
    {
        $serialized = [
            'products' => array_map(function (CartProduct $product) {
                return $product->jsonSerialize();
            }, $this->getProducts())
        ];

        $price = $this->getTotalPrice();
        if (null !== $price) {
            $serialized['totalPrice'] = $price->jsonSerialize();
        }

        $priceFormatted = $this->getTotalPriceFormatted();
        if (null !== $priceFormatted) {
            $serialized['totalPriceFormatted'] = $priceFormatted;
        }

        return $serialized;
    }
}
