Feature: Category browsing
  In order to be able to view catalog products
  As a visitor
  I need to be able to see the products

  Scenario: Product data is displayed on category page
    When I am on "/furniture.html"
    Then I should see "Ottoman"
