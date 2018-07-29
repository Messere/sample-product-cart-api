<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\AddProductRequestValidator;
use Messere\Cart\Domain\Product\Command\AddProductCommand;
use Messere\Cart\Domain\Product\Product\ProductException;
use Ramsey\Uuid\UuidFactoryInterface;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AddProductController
{
    private $commandBus;
    private $validator;
    private $uuidFactory;

    public function __construct(
        CommandBus $commandBus,
        AddProductRequestValidator $validator,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
        $this->uuidFactory = $uuidFactory;
    }

    /**
     * @Route("/v1/product", methods={"POST"})
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function addProduct(Request $request): Response
    {
        $this->validator->assertValidRequest($request);

        $price = (array)$request->get('price', []);
        $command = new AddProductCommand(
            $request->get('name', ''),
            $price['amount'] ?? 0,
            $price['divisor'] ?? 0,
            strtoupper($price['currency'] ?? ''),
            $this->uuidFactory
        );

        try {
            $this->commandBus->handle($command);
        } catch (ProductException $e) {
            throw new BadRequestHttpException($e->getMessage(), $e);
        }

        return new JsonResponse([
            'id' => $command->getProductId(),
        ]);
    }
}
