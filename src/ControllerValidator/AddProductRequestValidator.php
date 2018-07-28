<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AddProductRequestValidator
{
    private $priceValidator;

    public function __construct(PriceValidator $priceValidator)
    {
        $this->priceValidator = $priceValidator;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function assertValidRequest(Request $request): void
    {
        $name = $request->get('name', '');
        if ('' === $name) {
            throw new BadRequestHttpException('Product name cannot be empty');
        }

        $price = (array)$request->get('price', []);
        $this->priceValidator->assertValidPrice($price);
    }
}
