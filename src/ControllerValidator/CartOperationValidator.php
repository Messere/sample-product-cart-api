<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartOperationValidator
{
    private $validator;

    public function __construct(UuidValidator $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function assertValidRequest(Request $request): void
    {
        $cartId = $request->get('cartId');
        $this->validator->assertValidUuid($cartId, 'Missing or invalid cartId');

        $productId = $request->get('productId');
        $this->validator->assertValidUuid($productId, 'Missing or invalid productId');
    }
}
