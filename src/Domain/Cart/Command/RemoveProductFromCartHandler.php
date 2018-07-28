<?php

namespace Messere\Cart\Domain\Cart\Command;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;

class RemoveProductFromCartHandler
{
    private $cartRepository;

    public function __construct(
        ICartRepository $cartRepository
    ) {
        $this->cartRepository = $cartRepository;
    }

    public function handle(RemoveProductFromCartCommand $command): void
    {
        $this->cartRepository->decreaseProductCountInCart(
            $command->getCartId(),
            $command->getProductId()
        );
    }
}
