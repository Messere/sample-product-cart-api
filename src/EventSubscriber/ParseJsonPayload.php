<?php

namespace Messere\Cart\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ParseJsonPayload implements EventSubscriberInterface

{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'injectJsonPayloadToRequest'
        ];
    }

    public function injectJsonPayloadToRequest(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        $content = $request->getContent();

        if ('' === $content || false === strpos('application/json', $request->getContentType())) {
            return;
        }

        $decodedContent = json_decode($content, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new BadRequestHttpException('Invalid JSON in request payload. ' . json_last_error_msg());
        }

        $request->request->replace((array)$decodedContent);
    }
}
