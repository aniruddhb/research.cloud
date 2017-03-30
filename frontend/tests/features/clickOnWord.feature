Feature: Click on word
  Scenario: Clicking on a word to display paper list
    Given I am on localhost/index.html
    And a word cloud is generated
    When I click on a word
    Then I see localhost/paperList.html
    And the paperListTitle is the paper
