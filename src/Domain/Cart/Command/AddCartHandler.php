<?php

namespace Messere\Cart\Domain\Cart\Command;

class AddCartHandler
{
    public function handle(/*AddCartCommand $command*/): void
    {
        // noop, we don't need a cart in database, any UUID will do
        // and we will later store it with product id
    }
}
