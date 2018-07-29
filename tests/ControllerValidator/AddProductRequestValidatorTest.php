<?php

namespace Messere\Cart\ControllerValidator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddProductRequestValidatorTest extends TestCase
{
    private $priceValidator;
    private $validator;
    private $request;

    private const NAME_KEY = 'name';
    private const PRICE_KEY = 'price';

    public function setUp(): void
    {
        parent::setUp();

        $this->priceValidator = $this->prophesize(PriceValidator::class);
        $this->priceValidator->assertValidRequest(Argument::any());
        $this->validator = new AddProductRequestValidator($this->priceValidator->reveal());
        $this->request = $this->prophesize(Request::class);
    }

    /**
     * @dataProvider nonScalarsProvider
     * @param $name
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testShouldThrowExceptionIfNameIsInvalid($name): void
    {
        $this->request->get(self::NAME_KEY)->willReturn($name);
        $this->request->get(self::PRICE_KEY)->willReturn(null);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    public function nonScalarsProvider(): array
    {
        return [
            [[]],
            [null],
        ];
    }

    public function testShouldPassValidName(): void
    {
        $this->request->get(self::NAME_KEY)->willReturn('z');
        $this->request->get(self::PRICE_KEY)->willReturn(null);
        $this->validator->assertValidRequest($this->request->reveal());
        // dummy, we just need to check if no exception is thrown
        $this->assertTrue(true);
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testShouldThrowExceptionIfPriceIsNotValid(): void
    {
        $this->priceValidator->assertValidRequest(Argument::any())->willThrow(
            new BadRequestHttpException()
        );
        $this->request->get(self::NAME_KEY)->willReturn('z');
        $this->request->get(self::PRICE_KEY)->willReturn(null);
        $this->validator->assertValidRequest($this->request->reveal());
    }
}
