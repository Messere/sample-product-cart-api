<?php

namespace Messere\Cart\Domain\Cart\Command;

use Messere\Cart\Domain\Cart\Cart\CartException;
use Messere\Cart\Domain\Cart\Repository\ICartRepository;

class AddProductToCartHandler
{
    public const CART_CAPACITY_LIMIT = 3;
    private $cartRepository;

    public function __construct(
        ICartRepository $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function handle(AddProductToCartCommand $command): void
    {
        $currentAmount = $this->cartRepository->getTotalAmount(
            $command->getCartId()
        );

        if ($currentAmount >= static::CART_CAPACITY_LIMIT) {
            throw new CartException('Cart capacity exceeded');
        }

        // note, we're not checking if product exists
        // it MAY appear after database synchronization
        // cart display needs to deal with this situation
        $this->cartRepository->increaseProductCountInCart(
            $command->getCartId(),
            $command->getProductId()
        );
    }
}
