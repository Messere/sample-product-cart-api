<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\RemoveProductRequestValidator;
use Messere\Cart\Domain\Product\Command\RemoveProductCommand;
use Ramsey\Uuid\Uuid;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RemoveProductController
{
    private $commandBus;
    private $validator;

    public function __construct(
        CommandBus $commandBus,
        RemoveProductRequestValidator $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    /**
     * @Route("/v1/product/{productId}", methods={"DELETE"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function removeProduct(Request $request): Response
    {
        $this->validator->assertValidRequest($request);

        $command = new RemoveProductCommand(
            Uuid::fromString(
                $request->get('productId')
            )
        );

        $this->commandBus->handle($command);

        return new Response('', 204);
    }
}
