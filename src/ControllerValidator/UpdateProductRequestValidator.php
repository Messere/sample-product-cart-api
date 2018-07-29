<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateProductRequestValidator
{
    private $priceValidator;

    public function __construct(PriceValidator $validator)
    {
        $this->priceValidator = $validator;
    }

    /**
     * @param Request $request
     * @throws BadRequestHttpException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidRequest(Request $request): void
    {
        $productId = $request->get('productId');

        if (null === $productId || !Uuid::isValid($productId)) {
            throw new BadRequestHttpException('Invalid or missing productId');
        }

        $name = $request->get('name');
        if (!\is_scalar($name)) {
            throw new BadRequestHttpException('Invalid product name');
        }

        $price = $request->get('price');

        if ($price !== null) {
            $this->priceValidator->assertValidRequest($price);
        }
    }
}
