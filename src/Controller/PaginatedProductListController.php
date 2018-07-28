<?php

namespace Messere\Cart\Controller;

use Messere\Cart\ControllerRequest\ProductPaginationRequest;
use Messere\Cart\ControllerResponse\PaginatedProductListResponseBuilder;
use Messere\Cart\Domain\Product\Query\IProductQuery;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaginatedProductListController
{
    private $productQuery;
    private $responseBuilder;

    public function __construct(
        IProductQuery $productQuery,
        PaginatedProductListResponseBuilder $responseBuilder
    ) {
        $this->productQuery = $productQuery;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @Route("/v1/product", methods={"GET"}, name="product_list")
     * @param Request $request
     * @return Response
     */
    public function getProducts(Request $request): Response
    {
        $productPaginationParams = new ProductPaginationRequest(
            $request->get('page')
        );
        return new JsonResponse(
            $this->responseBuilder->buildResponse(
                $this->productQuery->getProducts(
                    $productPaginationParams->getOffset(),
                    $productPaginationParams->getLimit()
                ),
                $productPaginationParams,
                ProductPaginationRequest::PAGE_SIZE,
                'product_list'
            )
        );
    }
}
