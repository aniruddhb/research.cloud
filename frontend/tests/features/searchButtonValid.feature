Feature: Search keyword when there is input
  Scenario: Search keyword invalid
    Given I am on localhost/index.html
    And I input an keyword
    When I click on searchButton
    Then I see a word cloud
