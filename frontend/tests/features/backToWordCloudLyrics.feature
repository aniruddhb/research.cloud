Feature: Returning to word cloud page from abstract
  Scenario: Returns to the previous word cloud page from the abstract page.
    Given I am on localhost/abstract.html
    When I click on backWordCloud
    Then I see localhost/index.html
