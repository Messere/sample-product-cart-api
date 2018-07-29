<?php

namespace Messere\Cart\Domain\Price;

use Esky\Enum\Enum;

/**
 * @method static PLN()
 * @method static EUR()
 */
class Currency extends Enum
{
    public const PLN = 'PLN';
    public const EUR = 'EUR';
}
