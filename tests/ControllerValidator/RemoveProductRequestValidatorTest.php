<?php

namespace Messere\Cart\ControllerValidator;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;

class RemoveProductRequestValidatorTest extends TestCase
{
    private $validator;
    private $request;

    public function setUp(): void
    {
        parent::setUp();
        $this->validator = new RemoveProductRequestValidator(
            new UuidValidator()
        );
        $this->request = $this->prophesize(Request::class);
    }

    /**
     * @dataProvider invalidUuidProvider
     * @param $productId
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function testInvalidProduct($productId): void
    {
        $this->request->get('productId')->willReturn($productId);
        $this->validator->assertValidRequest($this->request->reveal());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function invalidUuidProvider(): array
    {
        return [
            [null],
            [[]],
            ['zzz'],
        ];
    }

    /**
     * @throws \Exception
     */
    public function testValidProduct(): void
    {
        $this->request->get('productId')->willReturn(Uuid::uuid4()->toString());
        $this->validator->assertValidRequest($this->request->reveal());

        // dummy we just need to check if no exception is thrown
        $this->assertTrue(true);
    }
}
