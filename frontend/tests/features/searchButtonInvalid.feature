Feature: Search keyword when there is no input
  Scenario: Search keyword invalid
    Given I am on localhost/index.html
    And I do not input a keyword
    When I click on searchButton
    Then I see no difference
    And the searchButton is disabled
