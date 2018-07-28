<?php

namespace Messere\Cart\Domain\Cart\Command;

class AddCartHandler
{
    public function handle(AddCartCommand $command): void
    {
        // noop, we don't need a cart in database, just let handler generate new UUID for user
    }
}
