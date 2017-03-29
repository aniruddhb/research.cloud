Feature: Autocomplete keyword suggestions
  Scenario: Load keyword suggestions
    Given I am on localhost/index.html
    When I input more than three letters in the automplete-1
    Then I see a dropdown
