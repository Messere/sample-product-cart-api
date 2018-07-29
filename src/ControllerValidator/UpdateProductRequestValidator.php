<?php

namespace Messere\Cart\ControllerValidator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateProductRequestValidator
{
    private $priceValidator;
    private $uuidValidator;

    public function __construct(PriceValidator $validator, UuidValidator $uuidValidator)
    {
        $this->priceValidator = $validator;
        $this->uuidValidator = $uuidValidator;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidRequest(Request $request): void
    {
        $productId = $request->get('productId');
        $this->uuidValidator->assertValidUuid($productId, 'Invalid product id');

        $name = $request->get('name');
        if ($name !== null && !\is_scalar($name)) {
            throw new BadRequestHttpException('Invalid product name');
        }

        $price = $request->get('price');

        if ($price !== null) {
            $this->priceValidator->assertValidRequest($price);
        }
    }
}
