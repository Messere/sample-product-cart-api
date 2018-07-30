<?php

namespace Messere\Cart\ControllerResponse;

use Messere\Cart\ControllerRequest\ProductPaginationRequest;
use Messere\Cart\Domain\Product\Product\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginatedProductListResponseBuilder
{
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Product[] $products
     * @param ProductPaginationRequest $paginationRequest
     * @param int $productsPerPage
     * @param string $routeName
     * @return array
     */
    public function buildResponse(
        array $products,
        ProductPaginationRequest $paginationRequest,
        int $productsPerPage,
        string $routeName
    ): array {
        return [
            'products' => $this->buildProducts($products, $productsPerPage),
            '_links' => $this->buildLinks($products, $paginationRequest, $productsPerPage, $routeName)
        ];
    }

    /**
     * @param Product[] $products
     * @param int $productsPerPage
     * @return array
     */
    private function buildProducts(array $products, int $productsPerPage): array
    {
        $serializedProducts = [];
        foreach ($products as $productNumber => $product) {
            if ($productNumber >= $productsPerPage) {
                break;
            }
            $serializedProducts[$productNumber] = $product->jsonSerialize();
        }
        return $serializedProducts;
    }

    private function buildLinks(
        array $products,
        ProductPaginationRequest $paginationRequest,
        int $productsPerPage,
        string $routeName
    ): array {
        $currentPage = $paginationRequest->getPageNumber();

        $links =  [
            'self' => $this->buildLink($routeName, $currentPage),
        ];

        if ($currentPage > 1) {
            $links['previous'] = $this->buildLink($routeName, $currentPage - 1);
        }

        if (\count($products) > $productsPerPage) {
            $links['next'] = $this->buildLink($routeName, $currentPage + 1);
        }

        return $links;
    }

    private function buildLink(string $routeName, int $page): array
    {
        return [ 'href' => $this->urlGenerator->generate($routeName, [ 'page' => $page ]) ];
    }
}
