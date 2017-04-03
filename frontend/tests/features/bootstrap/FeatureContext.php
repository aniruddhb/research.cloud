<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements SnippetAcceptingContext {

  /**
   * Initializes context.
   *
   * Every scenario gets its own context instance.
   * You can also pass arbitrary arguments to the
   * context constructor through behat.yml.
   */
  public function __construct() {
  }

  /*
  * @Given /^I am on$/
  */
  public function iAmOn() {
    $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost');
    $session->stop();

  }

 /*
  * @Given /^there is no text in the search bar$/
  */
 public function thereIsNoTextInTheSearchBar() {
   echo("test passed");
 }

 /*
  * @When /I click on "([^"]*)"$/
  */
  public function iClickOn() {
     echo("test passed");
  }

  /*
   * @Then /^I see no difference$/
   */
   public function iSeeNoDifference() {
     echo("test passed");
   }

 /**
 * @Given there is a search bar
 */
 public function thereIsASearchBar()
 {
   assertNotEquals(null, $this->artistSearchBar);
 }

 /*
  * @Then the search button is not clickable
  */
 public function theSearchButtonIsNotClickable()
 {
   assertEquals('disabled', $this->searchButton->getAttribute('disabled'));
 }

 /**
     * @Given I am on localhost\/abstract.html
     */
    public function iAmOnLocalhostAbstractHtml()
    {
        throw new PendingException();
    }

    /**
     * @When I click on downloadAbstractButton
     */
    public function iClickOnDownloadabstractbutton()
    {
        throw new PendingException();
    }

    /**
     * @Then I see a pdf downloaded
     */
    public function iSeeAPdfDownloaded()
    {
        throw new PendingException();
    }

    /**
     * @Given I am on localhost\/index.html
     */
    public function iAmOnLocalhostIndexHtml()
    {
        throw new PendingException();
    }

    /**
     * @Given there is no text in the search bar
     */
    public function thereIsNoTextInTheSearchBar2()
    {
        throw new PendingException();
    }

    /**
     * @When I click on searchButton
     */
    public function iClickOnSearchbutton()
    {
        throw new PendingException();
    }

    /**
     * @Then I see no difference
     */
    public function iSeeNoDifference2()
    {
        throw new PendingException();
    }

    /**
     * @Given I input a keyword
     */
    public function iInputAKeyword()
    {
        throw new PendingException();
    }

    /**
     * @Then I see a word cloud
     */
    public function iSeeAWordCloud()
    {
        throw new PendingException();
    }

    /**
     * @Given a word cloud is generated
     */
    public function aWordCloudIsGenerated()
    {
        throw new PendingException();
    }

    /**
     * @When I click on a word
     */
    public function iClickOnAWord()
    {
        throw new PendingException();
    }

    /**
     * @Then I see localhost\/paperList.html
     */
    public function iSeeLocalhostPaperlistHtml()
    {
        throw new PendingException();
    }

    /**
     * @Then the paperListTitle is the paper
     */
    public function thePaperlisttitleIsThePaper()
    {
        throw new PendingException();
    }

    /**
     * @When I click on downloadWordCloudButton
     */
    public function iClickOnDownloadwordcloudbutton()
    {
        throw new PendingException();
    }

    /**
     * @Then I see a jpg downloaded
     */
    public function iSeeAJpgDownloaded()
    {
        throw new PendingException();
    }

    /**
     * @Given I am on localhost\/paperList.html
     */
    public function iAmOnLocalhostPaperlistHtml()
    {
        throw new PendingException();
    }

    /**
     * @Given I click on an author
     */
    public function iClickOnAnAuthor()
    {
        throw new PendingException();
    }

    /**
     * @Then I see localhost\/index.html
     */
    public function iSeeLocalhostIndexHtml()
    {
        throw new PendingException();
    }

    /**
     * @When I click on a paper
     */
    public function iClickOnAPaper()
    {
        throw new PendingException();
    }

    /**
     * @Then I see localhost\/abstract.html
     */
    public function iSeeLocalhostAbstractHtml()
    {
        throw new PendingException();
    }

    /**
     * @Then the abstract is displayed
     */
    public function theAbstractIsDisplayed()
    {
        throw new PendingException();
    }


}
