<?php

use PHPUnit\Framework\TestCase;

/*
 * @covers Scraper Class Methods
 */
final class ScraperTest extends TestCase {
  # tests for Scraper class

  // Test scrapeForPapers() function
  public function testScrapeForPapers() {
    # test info and setup
    echo "TEST NAME: testScrapeForPapers \n \n";
    $scraper = new Scraper();
    $results = $scraper->scrapeForPapers('halfond', '1');
    
    # scan pdfs directory to check paper counts
    $files = scandir(__DIR__ . '/../../pdfs/');

    # test assertions
    $this->assertSame(4, count($files));
  }

  // Test scrapeForAbstract() function (TODO: Implement once abstract is connected)
  public function testScrapeForAbstract() {
    # test info and setup
    echo "TEST NAME: testScrapeForAbstract \n \n";
    $scraper = new Scraper();
    $results = $scraper->scrapeForAbstract('1');

    # test assertions
    $this->assertSame("", $results);
  }
}
?>
