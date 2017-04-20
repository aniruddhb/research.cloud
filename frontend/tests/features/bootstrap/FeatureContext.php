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
  //  echo("test passed");
 }

 /*
  * @When /I click on "([^"]*)"$/
  */
  public function iClickOn() {
    //  echo("test passed");
  }

  /*
   * @Then /^I see no difference$/
   */
   public function iSeeNoDifference() {
    //  echo("test passed");
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
      // echo("test passed");
    }

    /**
     * @When I click on downloadAbstractButton
     */
    public function iClickOnDownloadabstractbutton()
    {
      // echo("test passed");
    }

    /**
     * @Then I see a pdf downloaded
     */
    public function iSeeAPdfDownloaded()
    {
      // echo("test passed");
    }

    /**
     * @Given I am on localhost\/index.html
     */
    public function iAmOnLocalhostIndexHtml()
    {

    }

    /**
     * @Given there is no text in the search bar
     */
    public function thereIsNoTextInTheSearchBar2()
    {

    }

    /**
     * @When I click on searchButton
     */
    public function iClickOnSearchbutton()
    {

    }

    /**
     * @Then I see no difference
     */
    public function iSeeNoDifference2()
    {

    }

    /**
     * @Given I input a keyword
     */
    public function iInputAKeyword()
    {

    }

    /**
     * @Then I see a word cloud
     */
    public function iSeeAWordCloud()
    {

    }

    /**
     * @Given a word cloud is generated
     */
    public function aWordCloudIsGenerated()
    {

    }

    /**
     * @When I click on a word
     */
    public function iClickOnAWord()
    {

    }

    /**
     * @Then I see localhost\/paperList.html
     */
    public function iSeeLocalhostPaperlistHtml()
    {

    }

    /**
     * @Then the paperListTitle is the paper
     */
    public function thePaperlisttitleIsThePaper()
    {

    }

    /**
     * @When I click on downloadWordCloudButton
     */
    public function iClickOnDownloadwordcloudbutton()
    {

    }

    /**
     * @Then I see a jpg downloaded
     */
    public function iSeeAJpgDownloaded()
    {

    }

    /**
     * @Given I am on localhost\/paperList.html
     */
    public function iAmOnLocalhostPaperlistHtml()
    {

    }

    /**
     * @Given I click on an author
     */
    public function iClickOnAnAuthor()
    {

    }

    /**
     * @Then I see localhost\/index.html
     */
    public function iSeeLocalhostIndexHtml()
    {

    }

    /**
     * @When I click on a paper
     */
    public function iClickOnAPaper()
    {

    }

    /**
     * @Then I see localhost\/abstract.html
     */
    public function iSeeLocalhostAbstractHtml()
    {

    }

    /**
     * @Then the abstract is displayed
     */
    public function theAbstractIsDisplayed()
    {

    }

    /**
      * @When I input a non numeric character in numPapers
      */
     public function iInputANonNumericCharacterInNumpapers()
     {

     }

     /**
     * @When I click on a conference title
     */
    public function iClickOnAConferenceTitle()
    {
        throw new PendingException();
    }

     /**
      * @Given I input a numeric character in numPapers
      */
     public function iInputANumericCharacterInNumpapers()
     {


     }

     /**
      * @Then I see a .txt downloaded
      */
     public function iSeeATxtDownloaded()
     {


     }

     /**
      * @Then I see a .pdf downloaded
      */
     public function iSeeAPdfDownloaded2()
     {
         throw new PendingException();
     }


}
