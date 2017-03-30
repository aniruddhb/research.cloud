Feature: Displays only X number of papers in the word cloud
  Scenario: Limits number of papers that are displayed in word cloud
    Given I am on localhost/index.html
    And I change X
    When I click on searchButton
    Then I see a word cloud
    And only X papers are used
