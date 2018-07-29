<?php

namespace Messere\Cart\ControllerValidator;

use PHPUnit\Framework\TestCase;

class PriceValidatorTest extends TestCase
{
    private $validator;

    private const DIVISOR = 'divisor';
    private const CURRENCY = 'currency';
    private const AMOUNT = 'amount';

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new PriceValidator();
    }

    /**
     * @dataProvider invalidPriceProvider
     * @param $price
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testShouldThrowExceptionOnInvalidPrice($price): void
    {
        $this->validator->assertValidRequest($price);
    }

    public function invalidPriceProvider(): array
    {
        return [
            [10],
            ['10 PLN'],
            [null],
            [[]],
            [self::DIVISOR => 1, self::CURRENCY => 'aaa'],
            [self::AMOUNT => 1, self::CURRENCY => 'bb'],
            [self::AMOUNT => 1, self::DIVISOR => 1],
            [self::AMOUNT => [], self::DIVISOR => 1, self::CURRENCY => 'sss'],
            [self::AMOUNT => 'aaa', self::DIVISOR => 1, self::CURRENCY => 'ddd'],
            [self::AMOUNT => 1, self::DIVISOR => [], self::CURRENCY => 'eee'],
            [self::AMOUNT => 1, self::DIVISOR => 'aaa', self::CURRENCY => 'fff'],
            [self::AMOUNT => 1, self::DIVISOR => 1, self::CURRENCY => []],
        ];
    }

    public function testShouldPassValidPrice(): void
    {
        $this->validator->assertValidRequest([
            self::AMOUNT => 1,
            self::DIVISOR => 1,
            self::CURRENCY => 'string',
        ]);

        // dummy, we just check if no exception is thrown
        $this->assertTrue(true);
    }
}
