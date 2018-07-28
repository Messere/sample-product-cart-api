<?php

namespace Messere\Cart\Controller;

use Messere\Cart\Domain\Cart\Cart\Cart;
use Messere\Cart\Domain\Cart\Query\ICartQuery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController
{
    private $cartQuery;

    public function __construct(
        ICartQuery $cartQuery
    ) {
        $this->cartQuery = $cartQuery;
    }

    /**
     * @Route("/v1/cart/{cartId}", methods={"GET"}, name="cart")
     * @param Request $request
     * @return Response
     */
    public function getProducts(Request $request): Response
    {
        return new JsonResponse(
            new Cart(
                $this->cartQuery->getProductsFromCart(
                    Uuid::fromString($request->get('cartId'))
                )
            )
        );
    }
}
