<?php

namespace Messere\Cart\ControllerResponse;

use Messere\Cart\ControllerRequest\ProductPaginationRequest;
use Messere\Cart\Domain\Price\Price;
use Messere\Cart\Domain\Product\Product\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginatedProductListResponseBuilder
{
    private $urlGenerator;

    /**
     * PaginatedProductListResponseBuilder constructor.
     * @param $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param Product[] $products
     * @param ProductPaginationRequest $paginationRequest
     * @param int $productsPerPageNumber
     * @param string $routeName
     * @return array
     */
    public function buildResponse(
        array $products,
        ProductPaginationRequest $paginationRequest,
        int $productsPerPageNumber,
        string $routeName
    ): array {
        return [
            'products' => $this->buildProducts($products, $productsPerPageNumber),
            '_links' => $this->buildLinks($products, $paginationRequest, $productsPerPageNumber, $routeName)
        ];
    }

    /**
     * @param Product[] $products
     * @param int $productsPerPageNumber
     * @return array
     */
    private function buildProducts(array $products, int $productsPerPageNumber): array
    {
        $serializedProducts = [];
        foreach ($products as $productNumber => $product) {
            if ($productNumber >= $productsPerPageNumber) {
                break;
            }
            $serializedProducts[$productNumber] = $product->jsonSerialize();
            $serializedProducts[$productNumber]['priceFormatted'] = $this->formatPrice($product->getPrice());
        }
        return $serializedProducts;
    }

    private function buildLinks(
        array $products,
        ProductPaginationRequest $paginationRequest,
        int $productsPerPageNumber,
        string $routeName
    ): array {
        $currentPage = $paginationRequest->getPageNumber();

        $links =  [
            'self' => $this->buildLink($routeName, $currentPage),
        ];

        if ($currentPage > 1) {
            $links['previous'] = $this->buildLink($routeName, $currentPage - 1);
        }

        if (\count($products) > $productsPerPageNumber) {
            $links['next'] = $this->buildLink($routeName, $currentPage + 1);
        }

        return $links;
    }

    private function buildLink(string $routeName, int $page): array
    {
        return [ 'href' => $this->urlGenerator->generate($routeName, [ 'page' => $page ]) ];
    }

    private function formatPrice(Price $price): string
    {
        return sprintf(
            '%.' . log10($price->getDivisor()) . 'f %s',
            $price->getAmount()/$price->getDivisor(),
            $price->getCurrency()->getName()
        );
    }
}
