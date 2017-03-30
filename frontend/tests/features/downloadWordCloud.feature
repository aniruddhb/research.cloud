Feature: download word cloud
  Scenario: click on download button for word cloud
    Given I am on localhost/index.html
    And I see a word cloud
    When I click on downloadWordCloudButton
    Then I see a jpg downloaded
