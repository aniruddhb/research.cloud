Feature: Searches a new keyword after a word cloud is generated
  Scenario: Search an keyword after already having a word cloud
    Given I am on localhost/index.html
    And there is a word cloud
    When I input an keyword
    And I press the searchButton
    Then I see a word cloud
    And keywordLabel is filled
