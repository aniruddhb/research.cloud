Feature: Shows word cloud after searching an keyword
  Scenario: Generate word cloud
    Given I am on localhost/index.html
    And I input an keyword in the automplete-1
    When I click on searchButton
    Then I see a word cloud
    And keywordLabel is filled 
    And the addButton and shareButton are visible
