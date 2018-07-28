<?php

namespace Messere\Cart\Controller;

use Messere\Cart\Domain\Cart\Command\AddCartCommand;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCartController
{
    private $commandBus;

    public function __construct(
        CommandBus $commandBus
    ) {
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/v1/cart", methods={"POST"})
     * @return Response
     */
    public function addProduct(): Response
    {
        $command = new AddCartCommand();
        $this->commandBus->handle($command);

        return new JsonResponse([
            'id' => $command->getCartId(),
        ]);
    }
}
