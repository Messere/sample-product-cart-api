<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\UpdateProductRequestValidator;
use Messere\Cart\Domain\Product\Command\UpdateProductCommand;
use Messere\Cart\Domain\Product\Command\UpdateProductHandler;
use Messere\Cart\Domain\Product\Product\ProductException;
use Ramsey\Uuid\UuidFactoryInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UpdateProductController
{
    private $commandBus;
    private $validator;
    private $uuidFactory;

    public function __construct(
        CommandBus $commandBus,
        UpdateProductRequestValidator $validator,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->uuidFactory = $uuidFactory;
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
            $this->uuidFactory->fromString($request->get('productId')),
            $request->get('name'),
            $price['amount'] ?? null,
            $price['divisor'] ?? null,
            ($price['currency'] ?? null) !== null ? strtoupper($price['currency']) : null
        );

        try {
            $this->commandBus->handle($command);
        } catch (ProductException $e) {
            if ($e->getCode() === UpdateProductHandler::PRODUCT_DOES_NOT_EXIST) {
                throw new NotFoundHttpException($e->getMessage(), $e);
            }
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new Response('', 204);
    }
}
