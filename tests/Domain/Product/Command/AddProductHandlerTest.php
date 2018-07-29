<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Product\Event\ProductCreatedEvent;
use Messere\Cart\Domain\Product\Product\Product;
use Messere\Cart\Domain\Product\Product\ProductBuilder;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use SimpleBus\Message\Bus\MessageBus;

class AddProductHandlerTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testShouldSaveProductInRepositoryAndSendToEventBus(): void
    {
        $repository = $this->prophesize(IProductRepository::class);
        $builder = $this->prophesize(ProductBuilder::class);
        $bus = $this->prophesize(MessageBus::class);

        $handler = new AddProductHandler(
            $repository->reveal(),
            $builder->reveal(),
            $bus->reveal()
        );

        $command = new AddProductCommand(
            'zzz',
            100,
            10,
            'PLN',
            new UuidFactory()
        );

        $productId = Uuid::uuid4();
        $product = $this->prophesize(Product::class);
        $product->getProductId()->willReturn($productId);

        /** @noinspection PhpParamsInspection */
        $builder->build(
            Argument::any(),
            'zzz',
            100,
            10,
            'PLN'
        )->willReturn(
            $product->reveal()
        );

        $repository->save($product->reveal())->shouldBeCalledTimes(1);
        $bus->handle(
            Argument::type(ProductCreatedEvent::class)
        )->shouldBeCalledTimes(1);

        $handler->handle($command);
    }
}
