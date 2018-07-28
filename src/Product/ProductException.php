<?php

namespace Messere\Cart\Product;

class ProductException extends \RuntimeException
{
    public function __construct(string $message, \Throwable $e)
    {
        parent::__construct($message, 0, $e);
    }
}
