<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\AddProductRequestValidator;
use Messere\Cart\Price\Currency;
use Messere\Cart\Price\Price;
use Messere\Cart\Product\AddProductCommand;
use SimpleBus\SymfonyBridge\Bus\CommandBus;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddProductController
{
    private $commandBus;
    private $validator;

    public function __construct(
        CommandBus $commandBus,
        AddProductRequestValidator $validator
    ) {
        $this->commandBus = $commandBus;
        $this->validator = $validator;
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

        $price = $request->get('price');
        $command = new AddProductCommand(
            $request->get('name'),
            new Price(
                $price['amount'],
                Currency::createFromConstantName(strtoupper($price['currency'])),
                $price['divisor']
            )
        );

        $this->commandBus->handle($command);

        return new Response('', 204);
    }
}
