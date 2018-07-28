<?php

namespace Messere\Cart\Infrastructure\Cart\Repository;

use Messere\Cart\Domain\Cart\Repository\ICartRepository;
use Ramsey\Uuid\UuidInterface;

class SqliteCartRepository implements ICartRepository
{
    private $pdo;

    public function __construct(
        \PDO $pdo
    ) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    private function getAmount(UuidInterface $cartId, UuidInterface $productId): int
    {
        $statement = $this->pdo->prepare(
            'select amount from cart where cart_id = :cartId and cartProduct_id = :cartProductId'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
            'cartProductId' => $productId->toString(),
        ]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result ? $result['amount'] : 0;
    }

    public function getTotalAmount(UuidInterface $cartId): int
    {
        $statement = $this->pdo->prepare(
            'select sum(amount) as amount from cart where cart_id = :cartId'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
        ]);

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result && $result['amount'] ? $result['amount'] : 0;
    }

    public function increaseProductCountInCart(UuidInterface $cartId, UuidInterface $productId): void
    {
        $currentAmount = $this->getAmount($cartId, $productId);
        if (0 === $currentAmount) {
            $this->insertFirstProduct($cartId, $productId);
        } else {
            $this->changeProductAmount($cartId, $productId, 1);
        }
    }

    public function decreaseProductCountInCart(UuidInterface $cartId, UuidInterface $productId): void
    {
        $currentAmount = $this->getAmount($cartId, $productId);
        if ($currentAmount > 1) {
            $this->changeProductAmount($cartId, $productId, -1);
        } else {
            $this->deleteProductFromCart($cartId, $productId);
        }
    }

    private function insertFirstProduct(UuidInterface $cartId, UuidInterface $productId): void
    {
        $statement = $this->pdo->prepare(
            'insert into cart (cart_id, cartProduct_id, amount)'
            . ' values (:cartId, :cartProductId, 1)'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
            'cartProductId' => $productId->toString(),
        ]);
    }

    private function changeProductAmount(UuidInterface $cartId, UuidInterface $productId, int $amountChange): void
    {
        $statement = $this->pdo->prepare(
            $amountChange > 0
             ? 'update cart set amount = amount + :change where cart_id = :cartId and cartProduct_id = :cartProductId'
             : 'update cart set amount = amount - :change where cart_id = :cartId and cartProduct_id = :cartProductId'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
            'cartProductId' => $productId->toString(),
            'change' => abs($amountChange),
        ]);
    }

    private function deleteProductFromCart(UuidInterface $cartId, UuidInterface $productId): void
    {
        $statement = $this->pdo->prepare(
            'delete from cart where cart_id = :cartId and cartProduct_id = :cartProductId'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
            'cartProductId' => $productId->toString(),
        ]);
    }
}
