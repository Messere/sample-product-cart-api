<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\CartOperationValidator;
use Messere\Cart\Domain\Cart\Cart\CartException;
use Messere\Cart\Domain\Cart\Command\AddProductToCartCommand;
use Ramsey\Uuid\UuidFactoryInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AddProductToCartController
{
    private $commandBus;
    private $validator;
    private $uuidFactory;

    public function __construct(
        CommandBus $commandBus,
        CartOperationValidator $validator,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @Route("/v1/cart/{cartId}/product", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request): Response
    {
        $this->validator->assertValidRequest($request);

        $command = new AddProductToCartCommand(
            $this->uuidFactory->fromString($request->get('cartId')),
            $this->uuidFactory->fromString($request->get('productId'))
        );

        try {
            $this->commandBus->handle($command);
        } catch (CartException $e) {
            throw new BadRequestHttpException(
                'Cannot add product to cart: ' . $e->getMessage(),
                $e
            );
        }

        return new Response('', 204);
    }
}
