Feature: The add button will add an keyword to the word cloud
  Scenario: Add an keyword
    Given I am on localhost/index.html
    And a word cloud is generated
    And I input an keyword in the search bar
    When I click on addButton
    Then I see an updated word cloud
    And there are two keyword names
