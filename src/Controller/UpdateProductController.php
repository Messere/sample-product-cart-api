<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\UpdateProductRequestValidator;
use Messere\Cart\Domain\Product\Command\UpdateProductCommand;
use Messere\Cart\Domain\Product\Product\ProductException;
use Messere\Cart\Domain\Product\Product\ProductValidationException;
use Ramsey\Uuid\Uuid;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UpdateProductController
{
    private $commandBus;
    private $validator;

    public function __construct(
        CommandBus $commandBus,
        UpdateProductRequestValidator $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
    }

    /**
     * @Route("/v1/product/{productId}", methods={"PATCH"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function updateProduct(Request $request): Response
    {
        $this->validator->assertValidRequest($request);

        $price = (array)$request->get('price', []);
        $command = new UpdateProductCommand(
            Uuid::fromString($request->get('productId')),
            $request->get('name'),
            $price['amount'] ?? null,
            $price['divisor'] ?? null,
            ($price['currency'] ?? null) !== null ? strtoupper($price['currency']) : null
        );

        try {
            $this->commandBus->handle($command);
        } catch (ProductException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new Response('', 204);
    }
}
