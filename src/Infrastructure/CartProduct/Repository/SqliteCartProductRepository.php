<?php

namespace Messere\Cart\Infrastructure\CartProduct\Repository;

use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Messere\Cart\Domain\CartProduct\Repository\ICartProductRepository;
use Ramsey\Uuid\UuidInterface;

class SqliteCartProductRepository implements ICartProductRepository
{
    private $pdo;

    public function __construct(
        \PDO $pdo
    ) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    public function remove(UuidInterface $productId): void
    {
        $statement = $this->pdo->prepare(
            'delete from cartProduct where id = :id'
        );

        $statement->execute([
            'id' => $productId->toString()
        ]);
    }

    public function save(CartProduct $product): void
    {
        $statement = $this->pdo->prepare(
            'insert or replace into cartProduct (id, name, price_amount, price_divisor, price_currency)'
            . ' values (:id, :name, :price_amount, :price_divisor, :price_currency)'
        );

        $statement->execute([
            'id' => $product->getId()->toString(),
            'name' => $product->getName(),
            'price_amount' => $product->getPrice()->getAmount(),
            'price_divisor' => $product->getPrice()->getDivisor(),
            'price_currency' => $product->getPrice()->getCurrency()->getValue()
        ]);
    }
}
