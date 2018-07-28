<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\CartOperationValidator;
use Messere\Cart\Domain\Cart\Cart\CartException;
use Messere\Cart\Domain\Cart\Command\AddProductToCartCommand;
use Ramsey\Uuid\Uuid;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AddProductToCartController
{
    private $commandBus;
    private $cartOperationValidator;

    public function __construct(
        CommandBus $commandBus,
        CartOperationValidator $cartOperationValidator
    ) {
        $this->commandBus = $commandBus;
        $this->cartOperationValidator = $cartOperationValidator;
    }

    /**
     * @Route("/v1/cart/{cartId}/product", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function addProduct(Request $request): Response
    {
        $this->cartOperationValidator->assertValidRequest($request);

        $command = new AddProductToCartCommand(
            Uuid::fromString($request->get('cartId')),
            Uuid::fromString($request->get('productId'))
        );

        try {
            $this->commandBus->handle($command);
        } catch (CartException $e) {
            throw new BadRequestHttpException(
                'Cannot add product to cart: ' . $e->getMessage(),
                0,
                $e
            );
        }

        return new Response('', 204);
    }
}
