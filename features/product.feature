Feature: Product
  As an API client
  In order to manage Product catalog
  I need to be able to create, update, delete and list products

  Scenario: Add product to catalog
    Given I have a request payload:
    """
    {
      "name": "Solaris",
      "price": {
        "amount": 2500,
        "divisor": 100,
        "currency": "PLN"
      }
    }
    """
    When I request "/v1/product" using "POST"
    Then the response status code should be 200
    And "id" property should be set to uuid
    And product "Solaris" 2500 / 100 "PLN" should be in database

  Scenario Outline: Validate input data when adding product
    Given I have a request payload:
    """
    <payload>
    """
    When I request "/v1/product" using "POST"
    Then the response status code should be 400

    Examples:
    | payload |
    | {       |
    | "aaa"   |
    | { "price": { "amount": 2500, "divisor": 100, "currency": "PLN" } } |
    | { "name": "", "price": { "amount": 2500, "divisor": 100, "currency": "PLN" } } |
    | { "name": [], "price": { "amount": 2500, "divisor": 100, "currency": "PLN" } } |
    | { "name": "a" } |
    | { "name": "a", "price": "100" } |
    | { "name": "a", "price": {  } } |
    | { "name": "a", "price": { "divisor": 100, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": "aaa", "divisor": 100, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 101, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 0, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": -100, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 100.12, "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": "aaa", "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": [], "currency": "PLN" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 100  } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 100, "currency": "USD" } } |
    | { "name": "a", "price": { "amount": 2500, "divisor": 100, "currency": [] } } |

  Scenario: Remove product from catalog
    Given I have a product with "id" "a9437a55-3169-4946-95e4-cde0e608e059" in database
    When I request "/v1/product/a9437a55-3169-4946-95e4-cde0e608e059" using "DELETE"
    Then the response status code should be 204
    And product should be gone

  Scenario: Should not complain when removing product that does not exist
    Given I don't have a product with "id" "dfb3b73c-600d-4786-a17f-d7999bb2e920" in database
    When I request "/v1/product/dfb3b73c-600d-4786-a17f-d7999bb2e920" using "DELETE"
    Then the response status code should be 204
    And product should be gone

  Scenario: Validate input data when removing product
    Given I request "/v1/product/zzz" using "DELETE"
    Then the response status code should be 400

  Scenario: Update product
    Given I have a product with "name" "The Godfather" in database
    And I have a request payload:
    """
    {
      "name": "The Godfather 2",
      "price": {
        "amount": 35,
        "divisor": 1,
        "currency": "PLN"
      }
    }
    """
    When I request "/v1/product/a9437a55-3169-4946-95e4-cde0e608e059" using "PATCH"
    Then the response status code should be 204
    And product "The Godfather 2" 35 / 1 "PLN" should be in database

  Scenario: Attempt to update product which does not exist
    Given I have a request payload:
    """
    {
      "name": "The Godfather 2",
      "price": {
        "amount": 35,
        "divisor": 1,
        "currency": "PLN"
      }
    }
    """
    And I request "/v1/product/dfb3b73c-600d-4786-a17f-d7999bb2e920" using "PATCH"
    Then the response status code should be 404

  Scenario: Validate product id when updating product
    And I have a request payload:
    """
    {
      "name": "The Godfather 2",
      "price": {
        "amount": 35,
        "divisor": 1,
        "currency": "PLN"
      }
    }
    """
    When I request "/v1/product/zzz" using "PATCH"
    Then the response status code should be 400

  Scenario Outline: Validate input data when updating product
    Given I have a request payload:
    """
    <payload>
    """
    When I request "/v1/product/a9437a55-3169-4946-95e4-cde0e608e059" using "PATCH"
    Then the response status code should be 400

    Examples:
      | payload |
      | {       |
      | { "name": "", "price": { "amount": 2500, "divisor": 100, "currency": "PLN" } } |
      | { "name": [], "price": { "amount": 2500, "divisor": 100, "currency": "PLN" } } |
      | { "name": "a", "price": "100" } |
      | { "name": "a", "price": {  } } |
      | { "name": "a", "price": { "divisor": 100, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": "aaa", "divisor": 100, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 101, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 0, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": -100, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 100.12, "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": "aaa", "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": [], "currency": "PLN" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 100  } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 100, "currency": "USD" } } |
      | { "name": "a", "price": { "amount": 2500, "divisor": 100, "currency": [] } } |

  Scenario: List products
    Given I request "/v1/product" using "GET"
    Then the response status code should be 200
    And response contains 3 products
    And response contains link to next page 2
    And response does not contain link to previous page 0

  Scenario: List products (second page)
    Given I request "/v1/product?page=2" using "GET"
    Then the response status code should be 200
    And response contains 3 products
    And response contains link to previous page 1
    And response does not contain link to next page 3

  Scenario Outline: List products should ignore invalid page and set it to 1
    Given I request "/v1/product?page=<page>" using "GET"
    Then the response status code should be 200
    And response contains link to self page 1

    Examples:
    | page |
    | zzz  |
    | []   |