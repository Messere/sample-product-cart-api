<?php

namespace Messere\Cart\Domain\Cart\Command;

use Messere\Cart\Domain\Cart\Cart\CartException;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;

class AddCartCommand
{
    private $cartId;

    public function __construct(UuidFactoryInterface $uuidFactory)
    {
        try {
            $this->cartId = $uuidFactory->uuid4();
        } catch (\Exception $e) {
            throw new CartException('Failed to generate new cart ID: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getCartId(): UuidInterface
    {
        return $this->cartId;
    }
}
