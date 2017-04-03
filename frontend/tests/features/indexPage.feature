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

  Scenario: Clicking on a word to display paper list
    Given I am on localhost/index.html
    And a word cloud is generated
    When I click on a word
    Then I see localhost/paperList.html
    And the paperListTitle is the paper

  Scenario: click on download button for word cloud
    Given I am on localhost/index.html
    And I see a word cloud
    When I click on downloadWordCloudButton
    Then I see a jpg downloaded
