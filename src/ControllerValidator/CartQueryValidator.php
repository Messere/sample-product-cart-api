<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartQueryValidator
{
    private $validator;

    public function __construct(UuidValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidRequest(Request $request): void
    {
        $cartId = $request->get('cartId');
        $this->validator->assertValidUuid($cartId, 'Invalid or missing cartId');
    }
}
