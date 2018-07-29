<?php

namespace Messere\Cart\Domain\Cart\Command;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RemoveProductFromCartHandlerTest extends TestCase
{
    private $repository;
    private $handler;

    public function  setUp(): void
    {
        parent::setUp();
        $this->repository = $this->prophesize(ICartRepository::class);
        $this->handler = new RemoveProductFromCartHandler($this->repository->reveal());
    }

    /**
     * @throws \Exception
     */
    public function  testShouldAddProductToRepository(): void
    {
        $command = new RemoveProductFromCartCommand(Uuid::uuid4(), Uuid::uuid4());
        $this->repository->decreaseProductCountInCart(
            $command->getCartId(),
            $command->getProductId()
        )->shouldBeCalledTimes(1);
        $this->handler->handle($command);
    }
}
