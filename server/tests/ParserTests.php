<?php

use PHPUnit\Framework\TestCase;

/*
 * @covers Parser Class Methods
 */
final class ParserTest extends TestCase {
	# tests for Parser class

	// Test parseAllResearchPapers() function
	public function testParseAllResearchPapers() {
		echo "TEST NAME: testParseAllResearchPapers \n \n";
		$parser = new Parser();
		$results = $parser->parseAllResearchPapers();
		$frequencies = $results[0];
		$paper_freq_counts = $results[1];
		$this->assertInternalType("array", $results);
		$this->assertInternalType("array", $frequencies);
		$this->assertInternalType("array", $paper_freq_counts);
		echo "PASS - Parse All Research Papers Test : Results and Results Tuple are all of Internal Type Array\n";
		$this->assertLessThanOrEqual(250, count($frequencies));
		$this->assertEquals(3, count($paper_freq_counts));
		echo "PASS - Parse All Research Papers Test : Result Frequency Array is of Length Less Than or Equal To 250\n";
		echo "PASS - Parse All Research Papers Test : Paper Frequency Counts Array is of Length 3\n";
		echo "Code Coverage : 23/58 statements = 39.6% | 4/4 branches = 100.0% \n \n";
	}

	// Test parseResearchPaper() function
	public function testParseResearchPaper() {
		echo "TEST NAME: testParseResearchPaper \n \n";
		$parser = new Parser();
		$overall_freq_count = array();
		$paper_freq_counts = array();
		$parser->parseResearchPaper("1.pdf", $overall_freq_count, $paper_freq_counts);
		$this->assertInternalType("array", $overall_freq_count);
		$this->assertInternalType("array", $paper_freq_counts);
		echo "PASS - Parse All Research Papers Test : Overall Frequency and Per-Paper Frequency Counts are all of Internal Type Array\n";
	}
}
?>