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
     */
    public function assertValidRequest(Request $request): void
    {
        $id = $request->get('productId');

        if (null === $id || !Uuid::isValid($id)) {
            throw new BadRequestHttpException('Invalid or missing productId');
        }

        $name = $request->get('name');
        if ($name !== null && !\is_scalar($name)) {
            throw new BadRequestHttpException('Invalid product name');
        }

        $price = $request->get('price');
        if ($price !== null && !\is_array($price)) {
            throw new BadRequestHttpException('Invalid product price');
        }
    }
}
