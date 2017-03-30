Feature: display abstract
  Scenario: Click on paper title to show abstract
    Given I am on localhost/paperList.html
    When I click on a paper
    Then I see localhost/abstract.html
    And the abstract is displayed
