Feature: Abstract page

  Scenario: click on download button for abstract
    Given I am on localhost/abstract.html
    When I click on downloadAbstractButton
    Then I see a pdf downloaded

  Scenario: Return to word cloud
    Given I am on localhost/abstract.html
    When I click on returnWordCloud
    Then I see localhost/index.html

  Scenario: Return to paper list
    Given I am on localhost/abstract.html
    When I click on returnPaperList
    Then I see localhost/paperList.html
