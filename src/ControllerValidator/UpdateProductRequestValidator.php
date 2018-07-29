<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateProductRequestValidator
{
    /**
     * @param Request $request
     * @throws BadRequestHttpException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidRequest(Request $request): void
    {
        $productId = $request->get('productId');

        if (null === $productId || !Uuid::isValid($productId)) {
            throw new BadRequestHttpException('Invalid or missing productId');
        }

        $name = $request->get('name');
        if (!\is_scalar($name)) {
            throw new BadRequestHttpException('Invalid product name');
        }

        $price = $request->get('price');
        if (!\is_array($price)) {
            throw new BadRequestHttpException('Invalid product price');
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
