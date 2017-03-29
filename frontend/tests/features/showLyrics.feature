Feature: Clicking on a song will transition to the abstract page
  Scenario: Shows the abstract page for the clicked song
    Given I am on localhost/paperList.html
    When I click on a song
    Then I see localhost/abstract.html
    And the word is highlighted
