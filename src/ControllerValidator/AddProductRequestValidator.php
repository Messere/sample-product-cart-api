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
        if ($name === null || !\is_scalar($name)) {
            throw new BadRequestHttpException('Missing or invalid product name');
        }

        $price = $request->get('price');
        if ($price === null || !\is_array($price)) {
            throw new BadRequestHttpException('Missing or invalid product price');
        }
    }
}
