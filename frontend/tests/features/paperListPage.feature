Feature: paper list page

  @javascript
  Scenario: when clicking on an author, it will do a new search
    Given I am on localhost/paperList.html
    And I click on an author
    When I click on an author
    Then I see localhost/index.html
    And I see a word cloud

  @javascript
  Scenario: Click on paper title to show abstract
    Given I am on localhost/paperList.html
    When I click on a paper
    Then I see localhost/abstract.html
    And the abstract is displayed

  @javascript
  Scenario: Download list of papers as .TXT
    Given I am on localhost/paperList.html
    When I click on exportToTXTButton
    Then I see a .txt downloaded

  @javascript
  Scenario: Download list of papers as .PDF
    Given I am on localhost/paperList.html
    When I click on exportToPDFButton
    Then I see a .pdf downloaded

  @javascript
  Scenario: Return to word cloud
    Given I am on localhost/paperList.html
    When I click on returnToWordCloudButton
    Then I see localhost/index.html
    And I see a word cloud

  @javascript
  Scenario: Generate new search with conference title
    Given I am on localhost/paperList.html
    When I click on a conference title
    Then I see localhost/index.html
    And I see a word cloud

  @javascript
  Scenario: Click on bibtex link to get bibtex
    Given I am on localhost/paperList.html
    When I click on a bibtex link
    Then I see a new page

  @javascript
  Scenario: Click on papers in paper list for a new search
    Given I am on localhost/paperList.html
    When I click on wordCloudSubsetOfPapersButton
    Then I see a word cloud
