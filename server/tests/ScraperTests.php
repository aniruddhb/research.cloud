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
    array_map('unlink', glob(__DIR__ . '/../../pdfs/*'));
    $results = $scraper->scrapeForPapers('halfond', '1');

    # scan pdfs directory to check paper counts
    $files = scandir(__DIR__ . '/../../pdfs/');

    # test assertions
    $this->assertInternalType("array", $files);
    echo "PASS - Test Scrape for Papers Type Test : Asserts Files Array Internal Type to be Array\n";
    $this->assertSame(3, count($files));
    echo "PASS - Test Scrape for Papers Count Test : Asserts that Files Array is of Size 3\n";
    echo "Code Coverage : 4/18 statements = 22.2% | 0/0 branches = 100% \n \n";
  }

  // Test scrapeForAbstract() function
  public function testScrapeForAbstract() {
    # test info and setup
    echo "TEST NAME: testScrapeForAbstract \n \n";
    $scraper = new Scraper();
    $scraper->scrapeForAbstract('1496657');

    # parse results from json
    $encoded_result = json_decode(file_get_contents(__DIR__ . '/../../scrapyACMAbs/abstract.json'), true);
    $result = $encoded_result["abstract"];
    $expected_result = "The goal of my work is to improve quality assurance techniques for web applications. I will develop automated techniques for modeling web applications and use these models to improve testing and analysis of web applications.";

    # test assertions
    $this->assertInternalType("string", $result);
    echo "PASS - Test Scrape for Abstract Type Test : Asserts that Abstract Result is of Type String\n";
    $this->assertSame($result, $expected_result);
    echo "PASS - Test Scrape for Abstract Equality Test : Asserts that Abstract Result is Same as Expected Result\n";
    echo "Code Coverage : 4/18 statements = 22.2% | 0/0 branches = 100% \n \n";

    # total coverage metrics
    echo "SCRAPER TEST - OVERALL RESULTS\n";
		echo "Overall Code Coverage : 8/18 statements = 44.5% | 0/0 branches = 100.0% \n \n";
  }
}
?>
