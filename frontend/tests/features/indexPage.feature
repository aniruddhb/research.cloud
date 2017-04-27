Feature: Index page
This page allows the user to type in an author's last name or a keyword.
Once the user presses search, a word cloud will be generated.

  Scenario: Search button will not be enabled if there is no text in the search bar
    Given I am on localhost/index.html
    And there is no text in the search bar
    When I click on searchButton
    Then I see no difference

  Scenario: Can search for an author
    Given I am on localhost/index.html
    And I input a keyword
    When I click on searchButton
    Then I see a word cloud

  Scenario: Generate a word cloud
    Given I am on localhost/index.html
    And I input a keyword
    When I click on searchButton
    Then I see a word cloud

  Scenario: Invalid input for X number of papers
    Given I am on localhost/index.html
    And I input a keyword
    When I input a non numeric character in numPapers
    Then I see no difference

  Scenario: Valid input for X number of papers
    Given I am on localhost/index.html
    And I input a keyword
    And I input a numeric character in numPapers
    When I click on searchButton
    Then I see a word cloud

  Scenario: Clicking on a word to display paper list
    Given I have clicked on a word in the word cloud
    Then I see localhost/paperList.html
    And the paperListTitle is the paper

  Scenario: Click on download button for word cloud
    Given I have clicked on a word in the word cloud
    When I click on downloadWordCloudButton
    Then I see a jpg downloaded

  Scenario: Status bar for the word cloud generation
    Given I am on localhost/index.html
    And I input a keyword
    When I click on searchButton
    Then I see a status bar

  Scenario: View previous searches in a dropdown
    Given I am on localhost/index.html
    And I input a keyword
    Then I see a dropdown
