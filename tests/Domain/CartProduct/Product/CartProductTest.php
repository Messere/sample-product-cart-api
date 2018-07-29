<?php

namespace Messere\Cart\Domain\CartProduct\Product;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartProductTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCartProduct(): void
    {
        $productId = Uuid::uuid4();
        $price = new Price(100, Currency::PLN(), 10);
        $product = new CartProduct(
            $productId,
            'test',
            $price,
            2
        );

        $this->assertEquals($productId, $product->getCartProductId());
        $this->assertEquals('test', $product->getName());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEquals(2, $product->getAmount());
        $this->assertEquals([
            'id' => $productId->toString(),
            'name' => 'test',
            'unitPrice' => [
                'amount' => 100,
                'divisor' => 10,
                'currency' => 'PLN',
            ],
            'amount' => 2,
        ], $product->jsonSerialize());
    }
}
