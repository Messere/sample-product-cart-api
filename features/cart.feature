Feature: Shopping cart
  As an API client
  In order to gather products to buy
  I need to be able to create cart, view cart, add and remove products from cart

  Scenario: Create cart
    Given I request "/v1/cart" using "POST"
    Then the response status code should be 200
    And "id" property should be set to uuid

  Scenario: Insert product to cart
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    Then the response status code should be 204
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain 1 product "a9437a55-3169-4946-95e4-cde0e608e059"
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 1 product

  Scenario Outline: Validate input data when adding product to cart
    Given I add product "<product>" to cart "<cart>"
    Then the response status code should be 400

    Examples:
    | product | cart |
    | zzz     | zzz  |
    | zzz     | d2442f96-4a17-42e3-a58e-d97d536e93c4 |
    | a9437a55-3169-4946-95e4-cde0e608e059 | zzz     |

  Scenario: Insert the same product twice to cart
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And the response status code should be 204
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And the response status code should be 204
    Then Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain 2 products "a9437a55-3169-4946-95e4-cde0e608e059"
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 2 products

  Scenario: Insert too much products into cart
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    Then the response status code should be 400
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 3 products

  Scenario: Delete product from cart
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    Then Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 2 products
    When I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4/product/a9437a55-3169-4946-95e4-cde0e608e059" using "DELETE"
    Then the response status code should be 204
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 1 product
    When I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4/product/a9437a55-3169-4946-95e4-cde0e608e059" using "DELETE"
    Then the response status code should be 204
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 0 products
    When I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4/product/a9437a55-3169-4946-95e4-cde0e608e059" using "DELETE"
    Then the response status code should be 204
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 0 products

  Scenario Outline: Validate input data when adding deleting product from cart
    Given I request "/v1/cart/<cart>/product/<product>" using "DELETE"
    Then the response status code should be 400

    Examples:
      | product | cart |
      | zzz     | zzz  |
      | zzz     | d2442f96-4a17-42e3-a58e-d97d536e93c4 |
      | a9437a55-3169-4946-95e4-cde0e608e059 | zzz     |

  Scenario: Get contents of the cart
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "3cb23567-1691-419b-88e5-ba9e1d5e5950" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "3cb23567-1691-419b-88e5-ba9e1d5e5950" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    When I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4" using "GET"
    Then the response status code should be 200
    And the response contains 3 products with total price 15989 / 100 "PLN" ("159.89 PLN" in human readable form)
    And the response contains 1 "The Godfather"
    And the response contains 2 "Steve Jobs"

  Scenario: Get contents of empty cart
    Given I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4" using "GET"
    Then the response status code should be 200
    And the response should equal to:
    """
    {
      "products": []
    }
    """

  Scenario: Validate input data when getting contents of the cart
    Given I request "/v1/cart/zzz" using "GET"
    Then the response status code should be 400

  Scenario: Deleted product should vanish form all carts
    Given I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    And I add product "a9437a55-3169-4946-95e4-cde0e608e059" to cart "24ef8d12-8d00-4adb-b0b3-f795e781dda0"
    When I request "/v1/product/a9437a55-3169-4946-95e4-cde0e608e059" using "DELETE"
    Then the response status code should be 204
    And Cart "d2442f96-4a17-42e3-a58e-d97d536e93c4" should contain total 0 products
    And Cart "24ef8d12-8d00-4adb-b0b3-f795e781dda0" should contain total 0 products

  Scenario: Allow adding product that does not exist, but don't show it in cart
    Given I add product "39d4523a-d8cd-489f-a962-218402b83b7c" to cart "d2442f96-4a17-42e3-a58e-d97d536e93c4"
    Given I request "/v1/cart/d2442f96-4a17-42e3-a58e-d97d536e93c4" using "GET"
    Then the response status code should be 200
    And the response should equal to:
    """
    {
      "products": []
    }
    """
