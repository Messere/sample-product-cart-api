<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CartOperationValidator
{
    /**
     * @param Request $request
     * @throws BadRequestHttpException
     */
    public function assertValidRequest(Request $request): void
    {
        $cartId = $request->get('cartId');
        $this->validateUuid($cartId, 'Missing or invalid cartId');

        $productId = $request->get('productId');
        $this->validateUuid($productId, 'Missing or invalid productId');
    }

    /**
     * @param $uuid
     * @param string $message
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    private function validateUuid($uuid, string $message): void
    {
        if (null === $uuid || !\is_scalar($uuid) || !Uuid::isValid($uuid)) {
            throw new BadRequestHttpException($message);
        }
    }
}
