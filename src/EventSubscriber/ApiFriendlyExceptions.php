<?php

namespace Messere\Cart\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * show json payload on (some) exceptions
 */
class ApiFriendlyExceptions implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event): void
    {
        $exception = $event->getException();

        if ($exception instanceof HttpExceptionInterface) {
            $response = new JsonResponse([
                'status' => $exception->getStatusCode(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace(),
            ], $exception->getStatusCode(), $exception->getHeaders());

            $event->setResponse($response);
        }
    }
}
