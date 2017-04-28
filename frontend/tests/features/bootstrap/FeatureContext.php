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

  /**
   * @Given I am on localhost\/index.html
   */
  public function iAmOnLocalhostIndexHtml()
  {
    // $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $driver = new \Behat\Mink\Driver\Selenium2Driver();
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost:8081');
  }

  /**
   * @Given there is no text in the search bar
   */
  public function thereIsNoTextInTheSearchBar2()
  {
    assertEquals("", $page->findField("automplete-1")->getValue());
  }

  /**
   * @Given I input a keyword
   */
  public function iInputAKeyword()
  {
    $page = $session->getPage();
    $textbox = $page->findField("automplete-1");
    $textbox->setValue("Halfond");
  }

  /**
    * @When I input a non numeric character in numPapers
    */
   public function iInputANonNumericCharacterInNumpapers()
   {
     $page = $session->getPage();
     $textbox = $page->findField("numPapers");
     $textbox->setValue("zero");
   }

   /**
    * @Given I input a numeric character in numPapers
    */
   public function iInputANumericCharacterInNumpapers()
   {
     $page = $session->getPage();
     $textbox = $page->findField("numPapers");
     $textbox->setValue("10");
   }

  /**
   * @When I click on searchButton
   */
  public function iClickOnSearchbutton()
  {
    $button = $page->findButton('searchButton');
    $button->mouseOver();
    $button->click();
  }

  /**
   * @Then I see localhost\/index.html
   */
  public function iSeeLocalhostIndexHtml()
  {
    $page = $session->getPage();
    $webString = "http://localhost:8081";
    if ($session->getCurrentUrl() != $webString)
    {
       throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
  }

  /**
   * @Then I see a word cloud
   */
  public function iSeeAWordCloud()
  {
    $page = $session->getPage();
    $webString = "http://localhost:8080/api/wordcloud/halfond/10";
    if ($session->getCurrentUrl() != $webString)
    {
      throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
  }

  /**
   * @Given I have clicked on a word in the word cloud
   */
  public function iHaveClickedOnAWordInTheWordCloud()
  {
    $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost:8081');
    $page = $session->getPage();
    $textbox = $page->findField("automplete-1");
    $textbox->setValue("Halfond");
    $button = $page->findButton('searchButton');
    $button->mouseOver();
    $button->click();
    $page = $session->getPage();
    $webString = "http://localhost:8080/api/wordcloud/halfond/10";
    if ($session->getCurrentUrl() != $webString)
    {
        throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
    $wordClicked = $page->findLink("can");

    if($wordClicked == null)
    {
        throw new Exception ("There is no word: ".$wordClicked);
    }
    $wordClicked->mouseOver();
    $wordClicked->click();
  }

  /**
   * @When I click on downloadWordCloudButton
   */
  public function iClickOnDownloadwordcloudbutton()
  {
    $button = $page->findButton('downloadButton');
    $button->mouseOver();
    $button->click();
  }

  /**
   * @Then I see a jpg downloaded
   */
  public function iSeeAJpgDownloaded()
  {

  }

  /**
   * @Then I see a status bar
   */
  public function iSeeAStatusBar()
  {

  }

  /**
   * @Then I see a dropdown
   */
  public function iSeeADropdown()
  {

  }

  /**
   * @When I click on a word
   */
  public function iClickOnAWord()
  {
    $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost:8081');
    $page = $session->getPage();
    $textbox = $page->findField("automplete-1");
    $textbox->setValue("Halfond");
    $button = $page->findButton('searchButton');
    $button->mouseOver();
    $button->click();
    $page = $session->getPage();
    $webString = "http://localhost:8080/api/wordcloud/halfond/10";
    if ($session->getCurrentUrl() != $webString)
    {
       throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
     $wordClicked = $page->findLink("can");
     if($wordClicked == null)
     {
         throw new Exception ("There is no word: ".$wordClicked);
     }
     $wordClicked->mouseOver();
     $wordClicked->click();
  }

  /**
   * @Then I see localhost\/paperList.html
   */
  public function iSeeLocalhostPaperlistHtml()
  {
    $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost:8081');
    $page = $session->getPage();
    $textbox = $page->findField("automplete-1");
    $textbox->setValue("Halfond");
    $button = $page->findButton('searchButton');
    $button->mouseOver();
    $button->click();
    $page = $session->getPage();
    $webString = "http://localhost:8080/api/wordcloud/halfond/10";
    if ($session->getCurrentUrl() != $webString)
    {
       throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
     $wordClicked = $page->findLink("can");
     if($wordClicked == null)
     {
         throw new Exception ("There is no word: ".$wordClicked);
     }
     $wordClicked->mouseOver();
     $wordClicked->click();
     $webString = "http://localhost:8080/api/papers/can";
     if ($session->getCurrentUrl() != $webString)
     {
         throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
     }
  }

  /**
   * @Then the paperListTitle is the paper
   */
  public function thePaperlisttitleIsThePaper()
  {

  }

  /**
   * @Given I am on localhost\/paperList.html
   */
  public function iAmOnLocalhostPaperlistHtml()
  {
    $driver = new \Behat\Mink\Driver\Selenium2Driver('firefox');
    $session = new \Behat\Mink\Session($driver);
    $session->start();
    $session->visit('http://localhost:8081');
    $page = $session->getPage();
    $textbox = $page->findField("automplete-1");
    $textbox->setValue("Halfond");
    $button = $page->findButton('searchButton');
    $button->mouseOver();
    $button->click();
    $page = $session->getPage();
    $webString = "http://localhost:8080/api/wordcloud/halfond/10";
    if ($session->getCurrentUrl() != $webString)
    {
       throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
    }
     $wordClicked = $page->findLink("can");
     if($wordClicked == null)
     {
         throw new Exception ("There is no word: ".$wordClicked);
     }
     $wordClicked->mouseOver();
     $wordClicked->click();
  }

  /**
   * @Given I click on an author
   */
  public function iClickOnAnAuthor()
  {

  }
  /**
   * @When I click on a paper
   */
  public function iClickOnAPaper()
  {

  }
  /**
    * @Given I am on localhost\/abstract.html
    */
   public function iAmOnLocalhostAbstractHtml()
   {
     // TODO: NEED TO COMPLETE
      $driver = new \Behat\Mink\Driver\Selenium2Driver();
      $session = new \Behat\Mink\Session($driver);
      $session->start();
      $session->visit('http://localhost:8081');
      $page = $session->getPage();
      $textbox = $page->findField("automplete-1");
      $textbox->setValue("Halfond");
      $button = $page->findButton('searchButton');
      $button->mouseOver();
      $button->click();
      sleep(10);
      $page = $session->getPage();
      $webString = "http://localhost:8080/api/wordcloud/halfond/10";
      if ($session->getCurrentUrl() != $webString)
      {
         throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
      }
       $wordClicked = $page->findLink("can");
       if($wordClicked == null)
       {
           throw new Exception ("There is no word: ".$wordClicked);
       }
       $wordClicked->mouseOver();
       $wordClicked->click();
       $webString = "http://localhost:8080/api/papers/can";
       if ($session->getCurrentUrl() != $webString)
       {
           throw new Exception ("The page is incorrect.".$session->getCurrentUrl());
       }
       $page = $session->getPage();
       // route to the abstract page
   }

  /**
   * @When I click on downloadAbstractButton
   */
  public function iClickOnDownloadabstractbutton()
  {
    $page = $session->getPage();
    $button = $page->findButton('downloadAbstractButton');
    $button->mouseOver();
    $button->click();
  }

  /**
   * @Then I see a pdf downloaded
   */
  public function iSeeAPdfDownloaded()
  {

  }

  /**
   * @Then I see no difference
   */
  public function iSeeNoDifference2()
  {

  }

  /**
   * @Then I see localhost\/abstract.html
   */
  public function iSeeLocalhostAbstractHtml()
  {
    assertNotEquals(null, $this->page->find("css", "#downloadAbstract"));
  }

  /**
   * @Then the abstract is displayed
   */
  public function theAbstractIsDisplayed()
  {
    assertNotEquals(null, $this->page->find("css", "#abstract"));
  }

   /**
   * @When I click on a conference title
   */
  public function iClickOnAConferenceTitle()
  {

  }


  /**
   * @When I click on a bibtex link
   */
  public function iClickOnABibtexLink()
  {

  }

  /**
   * @Then I see a new page
   */
  public function iSeeANewPage()
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

   }

    /**
     * @When I click on exportToTXTButton
     */
    public function iClickOnExporttotxtbutton()
    {
      $page = $session->getPage();
      $button = $page->findButton('exportToTXTButton');
      $button->mouseOver();
      $button->click();
    }

    /**
     * @When I click on exportToPDFButton
     */
    public function iClickOnExporttopdfbutton()
    {
      $page = $session->getPage();
      $button = $page->findButton('exportToPDFButton');
      $button->mouseOver();
      $button->click();
    }

    /**
     * @When I click on returnToWordCloudButton
     */
    public function iClickOnReturntowordcloudbutton()
    {
      $page = $session->getPage();
      $button = $page->findButton('returnToWordCloudButton');
      $button->mouseOver();
      $button->click();
    }

    /**
     * @When I click on returnWordCloud
     */
    public function iClickOnReturnwordcloud()
    {
      $page = $session->getPage();
      $button = $page->findButton('returnWordCloud');
      $button->mouseOver();
      $button->click();
    }

    /**
     * @When I click on returnPaperList
     */
    public function iClickOnReturnpaperlist()
    {
      $page = $session->getPage();
      $button = $page->findButton('returnPaperList');
      $button->mouseOver();
      $button->click();
    }

    /**
     * @When I click on wordCloudSubsetOfPapersButton
     */
    public function iClickOnWordcloudsubsetofpapersbutton()
    {
      $page = $session->getPage();
      $button = $page->findButton('wordCloudSubsetOfPapersButton');
      $button->mouseOver();
      $button->click();
    }

    /**
      * @When I click on downloadPaperHighlighted
      */
    public function iClickOnDownloadpaperhighlighted()
    {
      $page = $session->getPage();
      $button = $page->findButton('downloadPaperHighlighted');
      $button->mouseOver();
      $button->click();
    }


}
