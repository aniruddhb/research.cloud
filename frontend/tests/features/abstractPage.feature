Feature: Abstract page

  @javascript
  Scenario: click on download button for abstract
    Given I am on localhost/abstract.html
    When I click on downloadAbstractButton
    Then I see a pdf downloaded

  @javascript
  Scenario: Return to word cloud
    Given I am on localhost/abstract.html
    When I click on returnWordCloud
    Then I see localhost/index.html

  @javascript
  Scenario: Return to paper list
    Given I am on localhost/abstract.html
    When I click on returnPaperList
    Then I see localhost/paperList.html

  @javascript
  Scenario: click on download button for highlighted paper
    Given I am on localhost/abstract.html
    When I click on downloadPaperHighlighted
    Then I see a pdf downloaded
