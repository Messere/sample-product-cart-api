<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerValidator\CartQueryValidator;
use Messere\Cart\Domain\Cart\Cart\Cart;
use Messere\Cart\Domain\Cart\Query\ICartQuery;
use Ramsey\Uuid\UuidFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController
{
    private $cartQuery;
    private $uuidFactory;
    private $validator;

    public function __construct(
        ICartQuery $cartQuery,
        UuidFactoryInterface $uuidFactory,
        CartQueryValidator $validator
    ) {
        $this->cartQuery = $cartQuery;
        $this->uuidFactory = $uuidFactory;
        $this->validator = $validator;
    }

    /**
     * @Route("/v1/cart/{cartId}", methods={"GET"}, name="cart")
     * @param Request $request
     * @return Response
     */
    public function getProducts(Request $request): Response
    {
        $this->validator->assertValidRequest($request);

        return new JsonResponse(
            new Cart(
                $this->cartQuery->getProductsFromCart(
                    $this->uuidFactory->fromString($request->get('cartId'))
                )
            )
        );
    }
}
