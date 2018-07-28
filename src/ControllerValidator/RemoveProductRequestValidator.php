<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RemoveProductRequestValidator
{
    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function assertValidRequest(Request $request): void
    {
        if (!Uuid::isValid($request->get('productId', ''))) {
            throw new BadRequestHttpException('Invalid product id');
        }
    }
}
