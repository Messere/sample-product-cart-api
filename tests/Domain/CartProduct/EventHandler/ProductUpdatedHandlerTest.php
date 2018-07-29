<?php

namespace Messere\Cart\Domain\CartProduct\EventHandler;

use Messere\Cart\Domain\CartProduct\Repository\ProductCopier;
use Messere\Cart\Domain\Product\Event\ProductUpdatedEvent;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductUpdatedHandlerTest extends TestCase
{
    private $productCopier;
    private $eventHandler;

    public function setUp(): void
    {
        parent::setUp();
        $this->productCopier = $this->prophesize(ProductCopier::class);
        $this->eventHandler = new ProductUpdatedHandler($this->productCopier->reveal());
    }

    /**
     * @throws \Exception
     */
    public function testShouldCopyProduct(): void
    {
        $productId = Uuid::uuid4();
        $this->productCopier->copy($productId)->shouldBeCalledTimes(1);
        $event = new ProductUpdatedEvent($productId);
        $this->eventHandler->handle($event);
    }
}
