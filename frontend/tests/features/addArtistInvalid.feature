Feature: Adding an keyword to the word cloud
  Scenario: Add an invalid keyword
    Given I am on localhost/index.html
    And a word cloud is generated
    And I do not input an keyword in the search bar
    When I click on addButton
    Then I see no difference
    And the add button is disabled
