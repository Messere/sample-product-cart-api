<?php

use Assert\Assertion;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Messere\Cart\Infrastructure\PdoServiceFactory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ApiFeatureContext implements Context
{
    private $kernel;

    /**
     * @var Response
     */
    private $response;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var \PDO
     */
    private $pdo;

    private $entityId;

    private const PRODUCTS_KEY = 'products';
    private const AMOUNT_KEY = 'amount';

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @beforeScenario
     */
    public function prepareRequest(): void
    {
        copy(
            __DIR__ . '/../../doc/db-prototype.db',
            __DIR__ . '/../../var/test.db'
        );
        $this->pdo = PdoServiceFactory::createPdo();
        $this->request = new Request();
    }

    /**
     * @Given /^I have a request payload:$/
     * @param PyStringNode $payload
     */
    public function iHaveARequestPayload(PyStringNode $payload): void
    {
        $this->request->initialize(
            $this->request->query->all(),
            $this->request->request->all(),
            $this->request->attributes->all(),
            $this->request->cookies->all(),
            $this->request->files->all(),
            $this->request->server->all(),
            $payload->getRaw()
        );
    }

    /**
     * @When /^I request "([^"]*)" using "([^"]*)"$/
     * @param string $url
     * @param string $method
     * @throws Exception
     * @throws \Assert\AssertionFailedException
     */
    public function iRequestUsing(string $url, string $method): void
    {
        $urlParts = explode('?', $url);

        $query = $this->request->query->all();
        if ('' !== ($urlParts[1] ?? '')) {
            parse_str($urlParts[1], $query);
        }

        $this->request = $this->request->duplicate(
            $query,
            null,
            null,
            null,
            null,
            ['REQUEST_URI' => $urlParts[0]]
        );
        $this->request->setMethod($method);
        $this->request->headers->set('Content-Type', 'application/json');

        $this->response = $this->kernel->handle($this->request);
        Assertion::notNull($this->response, 'Expected response, but received none');

        // reset request
        $this->request = new Request();
    }

    /**
     * @Then /^the response status code should be (\d+)$/
     * @param int $statusCode
     * @throws \Assert\AssertionFailedException
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        Assertion::eq($this->response->getStatusCode(), $statusCode);
    }

    /**
     * @Given /^"([^"]*)" property should be set to uuid$/
     * @param string $key
     * @throws \Assert\AssertionFailedException
     */
    public function propertyShouldBeSetToUuid(string $key): void
    {
        Assertion::isJsonString($this->response->getContent());
        $jsonResponse = json_decode($this->response->getContent(), true);
        Assertion::keyExists($jsonResponse, $key);
        Assertion::true(Uuid::isValid($jsonResponse[$key]));
        $this->entityId = $jsonResponse[$key];
    }

    /**
     * @Given /^product "([^"]*)" (\d+) \/ (\d+) "([^"]*)" should be in database$/
     * @param string $name
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $currency
     * @throws \Assert\AssertionFailedException
     */
    public function productShouldBeInDatabase(string $name, int $priceAmount, int $priceDivisor, string $currency): void
    {
        foreach([
            'select name, price_amount, price_divisor, price_currency from product where id = :id',
            'select name, price_amount, price_divisor, price_currency from cartProduct where id = :id'
        ] as $query) {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $this->entityId]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            Assertion::isArray($result);

            Assertion::eq($result, [
                'name' => $name,
                'price_amount' => $priceAmount,
                'price_divisor' => $priceDivisor,
                'price_currency' => $currency,
            ]);
        }
    }

    /**
     * @Given /^I have a product with "([^"]*)" "([^"]*)" in database$/
     * @param string $key
     * @param string $value
     * @throws \Assert\AssertionFailedException
     */
    public function iHaveAProductWithIdInDatabase(string $key, string $value): void
    {
        $statement = $this->pdo->prepare('select id from product where ' . $key . ' = :value');
        $statement->execute(['value' => $value]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        Assertion::isArray($result);
        $this->entityId = $result['id'];
    }

    /**
     * @Given /^I don't have a product with "([^"]*)" "([^"]*)" in database$/
     * @param string $key
     * @param string $value
     * @throws \Assert\AssertionFailedException
     */
    public function iDontHaveAProductWithIdInDatabase(string $key, string $value): void
    {
        $statement = $this->pdo->prepare('select id from product where ' . $key . ' = :value');
        $statement->execute(['value' => $value]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        Assertion::false($result);
    }

    /**
     * @Given /^product should be gone$/
     * @throws \Assert\AssertionFailedException
     */
    public function productShouldBeGone(): void
    {
        foreach ([
            'select 1 from product where id = :id',
            'select 1 from cartProduct where id = :id'
        ] as $query) {
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $this->entityId]);
            $result = $statement->fetch(\PDO::FETCH_ASSOC);
            Assertion::false($result);
        }
    }

    /**
     * @Given /^response contains (\d+) products$/
     * @param int $numberOfProducts
     * @throws \Assert\AssertionFailedException
     */
    public function responseContainsProducts(int $numberOfProducts): void
    {
        Assertion::isJsonString($this->response->getContent());
        $jsonResponse = json_decode($this->response->getContent(), true);
        Assertion::keyExists($jsonResponse, static::PRODUCTS_KEY);
        Assertion::isArray($jsonResponse[static::PRODUCTS_KEY]);
        Assertion::count($jsonResponse[static::PRODUCTS_KEY], $numberOfProducts);
    }

    /**
     * @Given /^response (contains|does not contain) link to (previous|next|self) page (\d+)$/
     * @param string $containsString
     * @param string $link
     * @param int $page
     * @throws \Assert\AssertionFailedException
     */
    public function responseContainsLinkToNextPage(string $containsString, string $link, int $page): void
    {
        $contains = $containsString === 'contains';

        Assertion::isJsonString($this->response->getContent());
        $jsonResponse = json_decode($this->response->getContent(), true);

        $linksKey = '_links';

        Assertion::keyExists($jsonResponse, $linksKey);

        if ($contains) {
            Assertion::keyExists($jsonResponse[$linksKey], $link);
            Assertion::eq($jsonResponse[$linksKey][$link]['href'], "/v1/product?page=$page");
        } else {
            Assertion::keyNotExists($jsonResponse[$linksKey], $link);
        }
    }

    /**
     * @Given /^Cart "([^"]+)" should contain (\d+) products? "([^"]+)"$/
     * @param string $cartId
     * @param int $count
     * @param string $productId
     * @throws \Assert\AssertionFailedException
     */
    public function cartShouldContainProduct(string $cartId, int $count, string $productId): void
    {
        $statement = $this->pdo->prepare(
            'select amount from cart where cart_id = :cartId and cartProduct_id = :productId'
        );
        $statement->execute([ 'cartId' => $cartId, 'productId' => $productId ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        Assertion::isArray($result);
        Assertion::eq($count, $result[static::AMOUNT_KEY]);
    }

    /**
     * @Given /^Cart "([^"]+)" should contain total (\d+) products?$/
     * @param string $cartId
     * @param int $count
     * @throws \Assert\AssertionFailedException
     */
    public function cartShouldContainTotalProducts(string $cartId, int $count): void
    {
        $statement = $this->pdo->prepare(
            'select sum(amount) as amount from cart where cart_id = :cartId'
        );
        $statement->execute([ 'cartId' => $cartId ]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        Assertion::isArray($result);
        Assertion::eq($count, $result[static::AMOUNT_KEY]);
    }

    /**
     * @Given /^I add product "([^"]+)" to cart "([^"]+)"$/
     * @param string $productId
     * @param string $cartId
     * @throws \Assert\AssertionFailedException
     */
    public function iAddProductToCart(string $productId, string $cartId): void
    {
        $this->iHaveARequestPayload(new PyStringNode([
            json_encode(['productId' => $productId])
        ], 0));
        $this->iRequestUsing("/v1/cart/$cartId/product", 'POST');
    }

    /**
     * @Given /^the response contains (\d+) products with total price (\d+) \/ (\d+) "([^"]*)" \("([^"]*)" in human readable form\)$/
     * @param int $count
     * @param int $priceAmount
     * @param int $priceDivisor
     * @param string $currency
     * @param string $priceReadable
     * @throws \Assert\AssertionFailedException
     */
    public function theResponseContainsProductsWithTotalPriceInHumanReadableForm(
        int $count,
        int $priceAmount,
        int $priceDivisor,
        string $currency,
        string $priceReadable
    ): void {
        Assertion::isJsonString($this->response->getContent());
        $jsonResponse = json_decode($this->response->getContent(), true);

        Assertion::keyExists($jsonResponse, static::PRODUCTS_KEY);
        Assertion::isArray($jsonResponse[static::PRODUCTS_KEY]);

        $totalCount = array_reduce($jsonResponse[static::PRODUCTS_KEY], function (int $previous, array $item): int {
            Assertion::keyExists($item, 'amount');
            Assertion::integer($item['amount']);
            return $previous + $item['amount'];
        }, 0);

        Assertion::eq($count, $totalCount);

        Assertion::keyExists($jsonResponse, 'totalPrice');
        Assertion::eq([
            static::AMOUNT_KEY => $priceAmount,
            'divisor' => $priceDivisor,
            'currency' => $currency,
        ], $jsonResponse['totalPrice']);

        Assertion::keyExists($jsonResponse, 'totalPriceFormatted');
        Assertion::eq($priceReadable, $jsonResponse['totalPriceFormatted']);
    }

    /**
     * @Given /^the response contains (\d+) "([^"]*)"$/
     * @param int $count
     * @param string $name
     * @throws \Assert\AssertionFailedException
     */
    public function theResponseContains(int $count, string $name): void
    {
        Assertion::isJsonString($this->response->getContent());
        $jsonResponse = json_decode($this->response->getContent(), true);
        Assertion::keyExists($jsonResponse, static::PRODUCTS_KEY);


        $products = $jsonResponse[static::PRODUCTS_KEY];
        Assertion::isArray($products);

        Assertion::allKeyExists($products, 'name');
        Assertion::allKeyExists($products, static::AMOUNT_KEY);

        $found = false;
        foreach ($products as $product) {
            if ($product['name'] === $name) {
                $found = true;
                Assertion::eq($count, $product[static::AMOUNT_KEY]);
                break;
            }
        }

        Assertion::true($found);
    }

    /**
     * @Given /^the response should equal to:$/
     * @param PyStringNode $responseJson
     * @throws \Assert\AssertionFailedException
     */
    public function theResponseShouldEqualTo(PyStringNode $responseJson): void
    {
        Assertion::isJsonString($this->response->getContent());
        Assertion::eq(json_decode($responseJson->getRaw()), json_decode($this->response->getContent()));
    }
}
