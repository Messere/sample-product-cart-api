<?php


namespace Messere\Cart\ControllerValidator;


use Messere\Cart\Price\Currency;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PriceValidator
{

    /**
     * @param array $price
     * @throws BadRequestHttpException
     */
    public function assertValidPrice(array $price): void
    {
        $requiredKeys = ['amount', 'divisor', 'currency'];
        foreach ($requiredKeys as $key) {
            if (!\array_key_exists($key, $price) || '' === $price[$key]) {
                throw new BadRequestHttpException("Price: required key '$key' is missing or empty");
            }
        }

        if (!\is_int($price['amount'])) {
            throw new BadRequestHttpException('Price: amount must be an integer');
        }

        if (!\is_int($price['divisor']) || $price['divisor'] < 1) {
            throw new BadRequestHttpException('Price: divisor must be a positive integer');
        }

        if (!Currency::isValidValue($price['currency'])) {
            throw new BadRequestHttpException('Price: unsupported currency');
        }
    }
}
