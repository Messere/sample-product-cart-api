<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;
use Messere\Cart\Domain\CartProduct\Repository\ICartProductRepository;
use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductRemovedHandlerTest extends TestCase
{
    private $repository;
    private $cartRepository;
    private $eventHandler;

    public function setUp(): void
    {
        $this->repository = $this->prophesize(ICartProductRepository::class);
        $this->cartRepository = $this->prophesize(ICartRepository::class);
        $this->eventHandler = new ProductRemovedHandler(
            $this->repository->reveal(),
            $this->cartRepository->reveal()
        );
    }

    /**
     * @throws \Exception
     */
    public function testShouldRemoveProductFromRepository(): void
    {
        $productId = Uuid::uuid4();
        $event = new ProductRemovedEvent($productId);
        $this->repository->remove($productId)->shouldBeCalledTimes(1);
        $this->cartRepository->removeProductFromCarts($productId)->shouldBeCalledTimes(1);
        $this->eventHandler->handle($event);
    }
}
