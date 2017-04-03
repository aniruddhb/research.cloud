Feature: paper list page

  Scenario: when clicking on an author, it will do a new search
    Given I am on localhost/paperList.html
    And I click on an author
    When I click on an author
    Then I see localhost/index.html
    And I see a word cloud

  Scenario: Click on paper title to show abstract
    Given I am on localhost/paperList.html
    When I click on a paper
    Then I see localhost/abstract.html
    And the abstract is displayed
