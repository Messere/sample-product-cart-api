<?php

namespace Messere\Cart\Domain\Product\Command;

use Messere\Cart\Domain\Price\Currency;
use Messere\Cart\Domain\Price\Price;
use Messere\Cart\Domain\Price\PriceBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use Messere\Cart\Domain\Product\Event\ProductUpdatedEvent;
use Messere\Cart\Domain\Product\Product\Product;
use Messere\Cart\Domain\Product\Product\ProductValidationException;
use Messere\Cart\Domain\Product\Product\ProductValidator;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;
use SimpleBus\Message\Bus\MessageBus;

class UpdateProductHandlerTest extends TestCase
{
    private $repository;
    private $builder;
    private $validator;
    private $bus;
    private $handler;
    private $product;

    /**
     * @throws \Exception
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->prophesize(IProductRepository::class);
        $this->builder = $this->prophesize(PriceBuilder::class);
        $this->validator = $this->prophesize(ProductValidator::class);
        $this->bus = $this->prophesize(MessageBus::class);

        $this->product = new Product(
            Uuid::uuid4(),
            'Test',
            new Price(100, Currency::PLN(), 10)
        );

        $this->handler = new UpdateProductHandler(
            $this->repository->reveal(),
            $this->builder->reveal(),
            $this->validator->reveal(),
            $this->bus->reveal()
        );
    }

    /**
     * @expectedException \Messere\Cart\Domain\Product\Product\ProductException
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfProductDoesNotExist(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(null);
        $command = new UpdateProductCommand(Uuid::uuid4());
        $this->handler->handle($command);
    }

    /**
     * @throws \Exception
     */
    public function testShouldUpdateName(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(
            $this->product
        );

        $newName = 'new name';
        $this->validator->validate($this->product->withName($newName))->shouldBeCalled();
        $this->repository->save($this->product->withName($newName))->shouldBeCalledTimes(1);
        $this->bus->handle(
            new ProductUpdatedEvent($this->product->getProductId())
        )->shouldBeCalledTimes(1);

        $command = new UpdateProductCommand(
            Uuid::uuid4(),
            $newName
        );

        $this->handler->handle($command);
    }

    /**
     * @throws \Exception
     */
    public function testShouldUpdatePrice(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(
            $this->product
        );

        $newPrice = new Price(12, Currency::EUR(), 10);
        $this->validator->validate($this->product->withPrice($newPrice))->shouldBeCalled();
        $this->repository->save($this->product->withPrice($newPrice))->shouldBeCalledTimes(1);
        $this->bus->handle(
            new ProductUpdatedEvent($this->product->getProductId())
        )->shouldBeCalledTimes(1);

        $this->builder->buildPrice(12, 10, 'EUR')->willReturn(
            $newPrice
        );

        $command = new UpdateProductCommand(
            Uuid::uuid4(),
            null,
            $newPrice->getAmount(),
            $newPrice->getDivisor(),
            $newPrice->getCurrency()->getName()
        );

        $this->handler->handle($command);
    }

    /**
     * @throws \Exception
     */
    public function testShouldUpdateNothing(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(
            $this->product
        );

        $command = new UpdateProductCommand(
            Uuid::uuid4()
        );

        /** @noinspection PhpParamsInspection */
        $this->repository->save(Argument::any())->shouldNotBeCalled();
        $this->bus->handle(Argument::any())->shouldNotBeCalled();

        $this->handler->handle($command);
    }

    /**
     * @expectedException \Messere\Cart\Domain\Product\Product\ProductException
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfPriceIsInvalid(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(
            $this->product
        );

        $newPrice = new Price(12, Currency::EUR(), 10);
        $this->repository->save($this->product->withPrice($newPrice))->shouldNotBeCalled();
        $this->bus->handle(
            new ProductUpdatedEvent($this->product->getProductId())
        )->shouldNotBeCalled();

        $this->builder->buildPrice(12, 10, 'EUR')->willThrow(
            new PriceValidationException()
        );

        $command = new UpdateProductCommand(
            Uuid::uuid4(),
            null,
            $newPrice->getAmount(),
            $newPrice->getDivisor(),
            $newPrice->getCurrency()->getName()
        );

        $this->handler->handle($command);
    }

    /**
     * @expectedException \Messere\Cart\Domain\Product\Product\ProductException
     * @throws \Exception
     */
    public function testShouldThrowExceptionIfUpdatedProductIsInvalid(): void
    {
        /** @noinspection PhpParamsInspection */
        $this->repository->getById(Argument::any())->willReturn(
            $this->product
        );

        $newName = 'new name';
        $this->validator->validate($this->product->withName($newName))->willThrow(
            new ProductValidationException()
        );
        $this->repository->save($this->product->withName($newName))->shouldNotBeCalled();
        $this->bus->handle(
            new ProductUpdatedEvent($this->product->getProductId())
        )->shouldNotBeCalled();

        $command = new UpdateProductCommand(
            Uuid::uuid4(),
            $newName
        );

        $this->handler->handle($command);
    }
}
