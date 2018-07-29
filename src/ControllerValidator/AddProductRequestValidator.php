<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddProductRequestValidator
{
    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function assertValidRequest(Request $request): void
    {
        $name = $request->get('name');
        if (!\is_scalar($name)) {
            throw new BadRequestHttpException('Missing or invalid product name');
        }

        $price = $request->get('price');
        if (!\is_array($price)) {
            throw new BadRequestHttpException('Missing or invalid product price');
        }

        if (!\array_key_exists('amount', $price) || !\is_int($price['amount'])) {
            throw new BadRequestHttpException('Missing or invalid product price amount');
        }

        if (!\array_key_exists('divisor', $price) || !\is_int($price['divisor'])) {
            throw new BadRequestHttpException('Missing or invalid product price divisor');
        }

        if (!\array_key_exists('currency', $price) || !\is_string($price['currency'])) {
            throw new BadRequestHttpException('Missing or invalid product price currency');
        }
    }
}
