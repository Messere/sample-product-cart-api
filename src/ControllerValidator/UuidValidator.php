<?php

namespace Messere\Cart\ControllerValidator;

use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UuidValidator
{
    /**
     * @param $uuid
     * @param string $message
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function assertValidUuid($uuid, string $message): void
    {
        if (null === $uuid || !\is_scalar($uuid) || !Uuid::isValid($uuid)) {
            throw new BadRequestHttpException($message);
        }
    }
}
