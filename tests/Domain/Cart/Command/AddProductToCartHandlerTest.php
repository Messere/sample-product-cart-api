<?php

namespace Messere\Cart\Domain\Cart\Command;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class AddProductToCartHandlerTest extends TestCase
{
    private $repository;
    private $handler;

    public function  setUp(): void
    {
        parent::setUp();
        $this->repository = $this->prophesize(ICartRepository::class);
        $this->handler = new AddProductToCartHandler($this->repository->reveal());
    }

    /**
     * @throws \Exception
     */
    public function testShouldAddProductToRepository(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getTotalAmount(Argument::any())->willReturn(2);
        $command = new AddProductToCartCommand(Uuid::uuid4(), Uuid::uuid4());
        $this->repository->increaseProductCountInCart(
            $command->getCartId(),
            $command->getProductId()
        )->shouldBeCalledTimes(1);
        $this->handler->handle($command);
    }

    /**
     * @throws \Exception
     * @expectedException \Messere\Cart\Domain\Cart\Cart\CartException
     */
    public function testShouldThrowExceptionOnExceededCartLimit(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getTotalAmount(Argument::any())->willReturn(3);
        $command = new AddProductToCartCommand(Uuid::uuid4(), Uuid::uuid4());
        $this->repository->increaseProductCountInCart(
            $command->getCartId(),
            $command->getProductId()
        )->shouldNotBeCalled();
        $this->handler->handle($command);
    }
}
