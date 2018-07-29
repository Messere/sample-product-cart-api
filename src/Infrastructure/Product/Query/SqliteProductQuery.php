<?php

namespace Messere\Cart\Infrastructure\Product\Query;

use Messere\Cart\Domain\Product\Product\ProductBuilder;
use Messere\Cart\Domain\Product\Query\IProductQuery;
use Ramsey\Uuid\UuidFactoryInterface;

class SqliteProductQuery implements IProductQuery
{
    private $pdo;
    private $productBuilder;
    private $uuidFactory;

    public function __construct(
        \PDO $pdo,
        ProductBuilder $productBuilder,
        UuidFactoryInterface $uuidFactory
    ) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->productBuilder = $productBuilder;
        $this->uuidFactory = $uuidFactory;
    }

    public function getProducts(int $offset, int $limit): array
    {
        $statement = $this->pdo->prepare(
            'select id, name, price_amount, price_divisor, price_currency ' .
                'from product order by name, id limit :limit offset :offset'
        );

        $statement->execute([
            'offset' => $offset,
            'limit' => $limit
        ]);

        $products = [];
        while ($result = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = $this->productBuilder->build(
                $this->uuidFactory->fromString($result['id']),
                $result['name'],
                $result['price_amount'],
                $result['price_divisor'],
                \strtoupper($result['price_currency'])
            );
        }
        return $products;
    }
}
