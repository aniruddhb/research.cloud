Feature: A word cloud is generated
  Scenario: Generate a word cloud
    Given I am on localhost/index.html
    And I input a keyword
    When I click on searchButton
    Then I see a word cloud
    And keywordLabel is filled
    And downloadWordCloudButton is visible
