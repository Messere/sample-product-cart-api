<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Event\ProductRemovedEvent;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;

class RemoveProductHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testShouldRemoveProductFromRepositoryAndSendToEventBus(): void
    {
        $repository = $this->prophesize(IProductRepository::class);
        $bus = $this->prophesize(MessageBus::class);

        $handler = new RemoveProductHandler(
            $repository->reveal(),
            $bus->reveal()
        );

        $productId = Uuid::uuid4();
        $command = new RemoveProductCommand(
            $productId
        );

        $repository->remove($productId)->shouldBeCalledTimes(1);
        $bus->handle(
            new ProductRemovedEvent($productId)
        )->shouldBeCalledTimes(1);

        $handler->handle($command);
    }
}
