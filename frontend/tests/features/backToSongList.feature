Feature: Returning to the song list page from the abstract page
  Scenario: Returns to the previous song list page from the abstract page.
    Given I am on localhost/abstract.html
    When I click on backpaperList
    Then I see localhost/paperList.html
