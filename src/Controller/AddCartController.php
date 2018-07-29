<?php

namespace Messere\Cart\Controller;

use Messere\Cart\Domain\Cart\Command\AddCartCommand;
use Ramsey\Uuid\UuidFactoryInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddCartController
{
    private $commandBus;
    private $uuidFactory;

    public function __construct(
        CommandBus $commandBus,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->commandBus = $commandBus;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @Route("/v1/cart", methods={"POST"})
     * @return Response
     */
    public function addProduct(): Response
    {
        $command = new AddCartCommand($this->uuidFactory);
        $this->commandBus->handle($command);

        return new JsonResponse([
            'id' => $command->getCartId(),
        ]);
    }
}
