<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartQueryValidator
{
    /**
     * @param Request $request
     * @throws BadRequestHttpException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidRequest(Request $request): void
    {
        $cartId = $request->get('cartId');
        if (null === $cartId || !\is_scalar($cartId) || !Uuid::isValid($cartId)) {
            throw new BadRequestHttpException('Invalid or missing cartId');
        }
    }
}
