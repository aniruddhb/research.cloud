Feature: download abstract
  Scenario: click on download button for abstract
    Given I am on localhost/abstract.html
    When I click on downloadAbstractButton
    Then I see a pdf downloaded
