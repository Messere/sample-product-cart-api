<?php

namespace Messere\Cart\ControllerValidator;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class CartOperationValidatorTest extends TestCase
{
    private $validator;
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new CartOperationValidator(
            new UuidValidator()
        );
        $this->request = $this->prophesize(Request::class);
    }

    /**
     * @dataProvider invalidUuidsProvider
     * @param $productId
     * @param $cartId
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testInvalidProductAndCart($productId, $cartId): void
    {
        $this->request->get('cartId')->willReturn($cartId);
        $this->request->get('productId')->willReturn($productId);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function invalidUuidsProvider(): array
    {
        return [
            [null, Uuid::uuid4()->toString()],
            [[], Uuid::uuid4()->toString()],
            ['zzz', Uuid::uuid4()->toString()],
            [Uuid::uuid4()->toString(), null],
            [Uuid::uuid4()->toString(), []],
            [Uuid::uuid4()->toString(), 'zzz'],
            ['aaa', 'zzz'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testValidProductAndCart(): void
    {
        $this->request->get('cartId')->willReturn(Uuid::uuid4()->toString());
        $this->request->get('productId')->willReturn(Uuid::uuid4()->toString());
        $this->validator->assertValidRequest($this->request->reveal());

        // dummy we just need to check if no exception is thrown
        $this->assertTrue(true);
    }

}
