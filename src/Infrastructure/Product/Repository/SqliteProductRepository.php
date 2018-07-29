<?php

namespace Messere\Cart\Infrastructure\Product\Repository;

use Messere\Cart\Domain\Product\Product\Product;
use Messere\Cart\Domain\Product\Product\ProductBuilder;
use Messere\Cart\Domain\Product\Repository\IProductRepository;
use Ramsey\Uuid\UuidInterface;

class SqliteProductRepository implements IProductRepository
{
    private $pdo;
    private $productBuilder;

    public function __construct(
        \PDO $pdo,
        ProductBuilder $productBuilder
    ) {
        $this->pdo = $pdo;
        $this->productBuilder = $productBuilder;
    }

    public function save(Product $product): void
    {
        $statement = $this->pdo->prepare(
            'insert or replace into product (id, name, price_amount, price_divisor, price_currency)'
            . ' values (:id, :name, :price_amount, :price_divisor, :price_currency)'
        );

        $statement->execute([
            'id' => $product->getProductId()->toString(),
            'name' => $product->getName(),
            'price_amount' => $product->getPrice()->getAmount(),
            'price_divisor' => $product->getPrice()->getDivisor(),
            'price_currency' => $product->getPrice()->getCurrency()->getValue()
        ]);
    }

    public function remove(UuidInterface $productId): void
    {
        $statement = $this->pdo->prepare(
            'delete from product where id = :id'
        );

        $statement->execute([
            'id' => $productId->toString()
        ]);
    }

    public function getById(UuidInterface $productId): ?Product
    {
        $statement = $this->pdo->prepare(
            'select name, price_amount, price_divisor, price_currency from product where id = :id limit 1'
        );

        $statement->execute([
            'id' => $productId->toString()
        ]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (false === $result) {
            return null;
        }

        return $this->productBuilder->build(
            $productId,
            $result['name'],
            $result['price_amount'],
            $result['price_divisor'],
            $result['price_currency']
        );
    }
}
