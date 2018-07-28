<?php

namespace Messere\Cart\Domain\Product\Product;

class ProductValidator
{
    /**
     * @param Product $product
     * @throws ProductValidationException
     */
    public function validate(Product $product): void
    {
        if ($product->getName() === '') {
            throw new ProductValidationException('Product name is empty');
        }

        if ($product->getPrice()->getAmount() < 1) {
            throw new ProductValidationException('Product price must be positive integer');
        }
    }
}
