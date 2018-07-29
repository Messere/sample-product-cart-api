<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class RemoveProductRequestValidator
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
        $productId = $request->get('productId');
        $this->validator->assertValidUuid($productId, 'Invalid product id');
    }
}
