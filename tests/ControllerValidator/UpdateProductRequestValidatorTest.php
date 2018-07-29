<?php

namespace Messere\Cart\ControllerValidator;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateProductRequestValidatorTest extends TestCase
{
    private $priceValidator;
    private $validator;
    private $request;

    public function setUp(): void
    {
        parent::setUp();

        $this->priceValidator = $this->prophesize(PriceValidator::class);
        $this->priceValidator->assertValidRequest(Argument::any());
        $this->validator = new UpdateProductRequestValidator($this->priceValidator->reveal(), new UuidValidator());
        $this->request = $this->prophesize(Request::class);
    }

    private function mockRequest($productId, $name, $price): void
    {
        $this->request->get('productId')->willReturn($productId);
        $this->request->get('name')->willReturn($name);
        $this->request->get('price')->willReturn($price);
    }

    /**
     * @dataProvider invalidUuidProvider
     * @param $productId
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testShouldThrowExceptionOnInvalidProductId($productId): void
    {
        $this->mockRequest($productId, 'zzz', []);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    /**
     * @dataProvider invalidStringProvider
     * @param $name
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Exception
     */
    public function testShouldThrowExceptionOnInvalidName($name): void
    {
        $this->mockRequest(Uuid::uuid4()->toString(), $name, []);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfPriceIsNotNullAndInvalid(): void
    {
        $this->priceValidator->assertValidRequest(Argument::any())->willThrow(
            new BadRequestHttpException()
        );
        $this->mockRequest(Uuid::uuid4()->toString(), 'zzz', []);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    /**
     * @throws \Exception
     */
    public function testShouldPassIfNameIsProvidedAndValid(): void
    {
        $this->mockRequest(Uuid::uuid4()->toString(), 'zzz', null);
        $this->validator->assertValidRequest($this->request->reveal());
        $this->assertTrue(true);
    }

    /**
     * @throws \Exception
     */
    public function testShouldPassIfPriceIsProvidedAndValid(): void
    {
        $this->mockRequest(Uuid::uuid4()->toString(), null, []);
        $this->validator->assertValidRequest($this->request->reveal());
        $this->assertTrue(true);
    }

    /**
     * @throws \Exception
     */
    public function testShouldPassIfNameAndPriceAreProvidedAndValid(): void
    {
        $this->mockRequest(Uuid::uuid4()->toString(), 'zzz', []);
        $this->validator->assertValidRequest($this->request->reveal());
        $this->assertTrue(true);
    }

    public function invalidUuidProvider(): array
    {
        return [
            [[]],
            [null],
            ['zzzz'],
        ];
    }

    public function invalidStringProvider(): array
    {
        return [
            [[]],
        ];
    }
}
