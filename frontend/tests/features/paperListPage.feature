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

  Scenario: Download list of papers as .TXT
    Given I am on localhost/paperList.html
    When I click exportToTXTButton
    Then I see a .txt downloaded

  Scenario: Download list of papers as .PDF
    Given I am on localhost/paperList.html
    When I click exportToPDFButton
    Then I see a .pdf downloaded

  Scenario: Return to word cloud
    Given I am on localhost/paperList.html
    When I click returnToWordCloudButton
    Then I see localhost/index.html
    And I see a word cloud

  Scenario: Generate new search with conference title
    Given I am on localhost/paperList.html
    When I click on a conference title
    Then I see localhost/index.html
    And I see a word cloud

  Scenario: Click on bibtex link to get bibtex
    Given I am on localhost/paperList.html
    When I click on a bibtex link
    Then I see a new page
