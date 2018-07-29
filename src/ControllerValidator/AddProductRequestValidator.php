<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddProductRequestValidator
{
    private $priceValidator;

    public function __construct(PriceValidator $validator)
    {
        $this->priceValidator = $validator;
    }

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
        $this->priceValidator->assertValidRequest($price);
    }
}
