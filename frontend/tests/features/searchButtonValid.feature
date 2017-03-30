Feature: Search keyword when there is input
  Scenario: Search keyword valid
    Given I am on localhost/index.html
    And I input a keyword
    When I click on searchButton
    Then I see a word cloud
