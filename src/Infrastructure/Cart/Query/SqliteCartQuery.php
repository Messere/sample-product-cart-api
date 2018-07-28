<?php

namespace Messere\Cart\Infrastructure\Cart\Query;

use Messere\Cart\Domain\Cart\Query\ICartQuery;
use Messere\Cart\Domain\CartProduct\Product\CartProduct;
use Messere\Cart\Domain\CartProduct\Product\CartProductBuilder;
use Messere\Cart\Domain\Price\PriceValidationException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class SqliteCartQuery implements ICartQuery
{
    private $pdo;
    private $cartProductBuilder;

    public function __construct(
        \PDO $pdo,
        CartProductBuilder $cartProductBuilder
    ) {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->cartProductBuilder = $cartProductBuilder;
    }

    /**
     * @param UuidInterface $cartId
     * @return CartProduct[]
     * @throws PriceValidationException
     */
    public function getProductsFromCart(UuidInterface $cartId): array
    {
        $statement = $this->pdo->prepare(
            'select cp.id, cp.name, cp.price_amount, cp.price_divisor, cp.price_currency, c.amount ' .
            'from cart c left join cartProduct cp  on c.cartProduct_id = cp.id ' .
            'where c.cart_id = :cartId and c.amount > 0 order by cp.name, cp.id'
        );

        $statement->execute([
            'cartId' => $cartId->toString(),
        ]);

        $products = [];
        while ($result = $statement->fetch(\PDO::FETCH_ASSOC)) {
            $products[] = $this->cartProductBuilder->build(
                Uuid::fromString($result['id']),
                $result['name'],
                $result['price_amount'],
                $result['price_divisor'],
                \strtoupper($result['price_currency']),
                $result['amount']
            );
        }
        return $products;
    }
}
