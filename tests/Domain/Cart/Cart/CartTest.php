<?php

namespace Messere\Cart\Domain\Cart\Cart;

use Messere\Cart\Domain\CartProduct\Product\CartProductBuilder;
use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\CurrencyValidator;
use Messere\Cart\Domain\Price\Price;
use Messere\Cart\Domain\Price\PriceBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use Messere\Cart\Domain\Price\PriceValidator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CartTest extends TestCase
{
    private const ID = 'id';
    private const NAME = 'name';
    private const AMOUNT = 'amount';
    private const UNIT_PRICE = 'unitPrice';
    private const DIVISOR = 'divisor';
    private const CURRENCY = 'currency';

    public function testCartWithoutProducts(): void
    {
        $cart = new Cart([]);
        $this->assertNull($cart->getTotalPriceFormatted());
        $this->assertNull($cart->getTotalPrice());
        $this->assertEquals([], $cart->getProducts());
        $this->assertEquals(['products'=>[]], $cart->jsonSerialize());
    }

    /**
     * @throws PriceValidationException
     * @throws \Exception
     */
    public function testCartWithProducts(): void
    {
        $builder = new CartProductBuilder(new PriceBuilder(new PriceValidator(), new CurrencyValidator()));
        $id1 = Uuid::uuid4();
        $id2 = Uuid::uuid4();
        $products = [
            $builder->build($id1, 'aaa', 1099, 100, 'PLN', 1),
            $builder->build($id2, 'bbb', 10990, 1000, 'PLN', 2),
        ];
        $cart = new Cart($products);

        $this->assertEquals($products, $cart->getProducts());
        $this->assertEquals(new Price(32970, Currency::PLN(), 1000), $cart->getTotalPrice());
        $this->assertEquals('32.970 PLN', $cart->getTotalPriceFormatted());
        $this->assertEquals([
            'products' => [
                [
                    self::ID => $id1->toString(),
                    self::NAME => 'aaa',
                    self::AMOUNT => 1,
                    self::UNIT_PRICE => [
                        self::AMOUNT => 1099,
                        self::DIVISOR => 100,
                        self::CURRENCY => 'PLN'
                    ]
                ],
                [
                    self::ID => $id2->toString(),
                    self::NAME => 'bbb',
                    self::AMOUNT => 2,
                    self::UNIT_PRICE => [
                        self::AMOUNT => 10990,
                        self::DIVISOR => 1000,
                        self::CURRENCY => 'PLN'
                    ]
                ],
            ],
            'totalPrice' => [
                self::AMOUNT => 32970,
                self::DIVISOR => 1000,
                self::CURRENCY => 'PLN'
            ],
            'totalPriceFormatted' => '32.970 PLN'
        ], $cart->jsonSerialize());
    }
}
