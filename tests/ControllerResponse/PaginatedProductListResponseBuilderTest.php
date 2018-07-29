<?php

namespace Messere\Cart\ControllerResponse;

use Messere\Cart\ControllerRequest\ProductPaginationRequest;
use Messere\Cart\Domain\Product\Product\Product;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaginatedProductListResponseBuilderTest extends TestCase
{
    private $urlGenerator;
    private $builder;
    private $product;

    private const PRODUCTS_KEY = 'products';
    private const LINKS_KEY = '_links';
    private const ROUTE_NAME = 'route';

    public function setUp(): void {
        parent::setUp();
        $this->urlGenerator = $this->prophesize(UrlGeneratorInterface::class);
        $this->urlGenerator->generate(Argument::any(), Argument::any())->willReturn('/url');
        $this->builder = new PaginatedProductListResponseBuilder($this->urlGenerator->reveal());
        $this->product = $this->prophesize(Product::class);
        $this->product->jsonSerialize()->willReturn(['product' => true]);
    }

    /**
     * @param int $num
     * @return array
     * @throws \Exception
     */
    private function generateProducts(int $num): array
    {
        $products = [];
        for (; $num > 0; $num--) {
            $products[] = $this->product->reveal();
        }
        return $products;
    }

    private function assertProductJson(int $count, array $links, array $response): void
    {
        $this->assertArrayHasKey(self::PRODUCTS_KEY, $response);
        $this->assertCount($count, $response[self::PRODUCTS_KEY]);
        foreach ($response[self::PRODUCTS_KEY] as $product) {
            $this->assertEquals(['product' => true], $product);
        }

        $this->assertArrayHasKey(self::LINKS_KEY, $response);
        $this->assertCount(\count($links), $response[self::LINKS_KEY]);
        foreach ($links as $linkName) {
            $this->assertEquals(['href' => '/url'], $response[self::LINKS_KEY][$linkName]);
        }
    }

    /**
     * @throws \Exception
     */
    public function testShouldBuildProductsWithNextLink(): void
    {
        $products = $this->generateProducts(4);
        $response = $this->builder->buildResponse(
            $products,
            new ProductPaginationRequest(1),
            3,
            self::ROUTE_NAME
        );
        $this->assertProductJson(3, ['self', 'next'], $response);
    }

    /**
     * @throws \Exception
     */
    public function testShouldBuildProductsWithPreviousLink(): void
    {
        $products = $this->generateProducts(3);
        $response = $this->builder->buildResponse(
            $products,
            new ProductPaginationRequest(2),
            3,
            self::ROUTE_NAME
        );
        $this->assertProductJson(3, ['self', 'previous'], $response);
    }

    /**
     * @throws \Exception
     */
    public function testShouldBuildProductsWithPreviousAndNextLinks(): void
    {
        $products = $this->generateProducts(4);
        $response = $this->builder->buildResponse(
            $products,
            new ProductPaginationRequest(2),
            3,
            self::ROUTE_NAME
        );
        $this->assertProductJson(3, ['self', 'previous', 'next'], $response);
    }

    /**
     * @throws \Exception
     */
    public function testShouldBuildProductsWithNoPreviousNextLinks(): void
    {
        $products = $this->generateProducts(2);
        $response = $this->builder->buildResponse(
            $products,
            new ProductPaginationRequest(1),
            3,
            self::ROUTE_NAME
        );
        $this->assertProductJson(2, ['self'], $response);
    }
}
