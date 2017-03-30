Feature: new author search
  Scenario: when clicking on an author, it will do a new search
    Given I am on localhost/abstract.html
    And I click on an author
    When I click on an author
    Then I see localhost/index.html
    And I see a word cloud
