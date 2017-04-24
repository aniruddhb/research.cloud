<?php

use PHPUnit\Framework\TestCase;

/*
 * @covers CacheManager Class Methods
 */
final class CacheManagerTest extends TestCase {
	# tests for CacheManager class

	// Test overall_freq_cache() function
	public function testOverallFrequencyCache() {
		echo "TEST NAME: testOverallFrequencyCache \n \n";
		$cache = new CacheManager();
		$this->assertInternalType("array", $cache->overall_freq_cache());
		echo "PASS - Overall Frequency Cache Type Test : Asserts Return Type to be Array\n";
		echo "Code Coverage : 4/71 statements = 5.6% | 0/4 branches = 0.0% \n \n";
	}

	// Test set_overall_freq_cache() function
	public function testSetOverallFrequencyCache() {
		echo "TEST NAME: testSetOverallFrequencyCache \n \n";
		$cache = new CacheManager();
		$input = array(array("test_input_one" => "freq_one"), array("test_input_two" => "freq_two"));
		$cache->set_overall_freq_cache($input);
		$this->assertSame($input, $cache->overall_freq_cache());
		echo "PASS - Overall Frequency Cache Setter Test : Asserts Input and Overall Cache to be Same\n";
		echo "Code Coverage : 3/71 statements = 4.2% | 0/4 branches = 0.0% \n \n";
	}

	// Test search_freq_cache() function
	public function testSearchFrequencyCache() {
		echo "TEST NAME: testSearchFrequencyCache \n \n";
		$cache = new CacheManager();
		$this->assertInternalType("array", $cache->search_freq_cache());
		echo "PASS - Search Frequency Cache Type Test : Asserts Return Type to be Array\n";
		echo "Code Coverage : 4/71 statements = 5.6% | 0/4 branches = 0.0% \n \n";
	}

	// Test set_search_freq_cache() function
	public function testSetSearchFrequencyCache() {
		echo "TEST NAME: testSetSearchFrequencyCache \n \n";
		$cache = new CacheManager();
		$input = array(array("test_input_one" => "freq_one"), array("test_input_two" => "freq_two"));
		$cache->set_search_freq_cache($input);
		$this->assertSame($input, $cache->search_freq_cache());
		echo "PASS - Search Frequency Cache Setter Test : Asserts Input and Search Cache to be Same\n";
		echo "Code Coverage : 3/71 statements = 4.2% | 0/4 branches = 0.0% \n \n";
	}

	// Test lifetime_freq_cache() function
	public function testLifetimeFrequencyCache() {
		echo "TEST NAME: testLifetimeFrequencyCache \n \n";
		$cache = new CacheManager();
		$this->assertInternalType("array", $cache->lifetime_freq_cache());
		echo "PASS - Lifetime Frequency Cache Type Test : Asserts Return Type to be Array\n";
		echo "Code Coverage : 4/71 statements = 5.6% | 0/4 branches = 0.0% \n \n";
	}

	// Test add_to_lifetime_cache() function
	public function testAddToLifetimeCache() {
		echo "TEST NAME: testAddToLifetimeCache \n \n";
		$cache = new CacheManager();
		$input = array(array("test_input_one" => "freq_one"), array("test_input_two" => "freq_two"));
		$cache->add_to_lifetime_cache("input_one", "cap_one", $input);
		$this->assertArrayHasKey("input_one cap_one", $cache->lifetime_freq_cache());
		echo "PASS - Add To Lifetime Cache Test : Asserts Input Key to be in Lifetime Cache\n";
		echo "Code Coverage : 3/71 statements = 4.2% | 0/4 branches = 0.0% \n \n";
	}

	// Test contains() function
	public function testCacheContainsSearch() {
		echo "TEST NAME: testCacheContainsSearch \n \n";
		$cache = new CacheManager();
		$input = array(array("test_input_one" => "freq_one"), array("test_input_two" => "freq_two"));
		$cache->add_to_lifetime_cache("input_one", "cap_one", $input);
		$this->assertSame(isset($cache->lifetime_freq_cache()['input_one cap_one']), $cache->contains('input_one cap_one'));
		echo "PASS - Cache Contains Search Validity Test : Asserts that Field 'input_one cap_one' is Set in Lifetime Cache\n";
		$this->assertSame(isset($cache->lifetime_freq_cache()['not_input_one cap_one']), $cache->contains('not_input_one cap_one'));
		echo "PASS - Cache Contains Search Validity Test : Asserts that Field 'not_input_one cap_one' is not Set in Lifetime Cache\n";
		echo "Code Coverage : 3/71 statements = 4.2% | 0/4 branches = 0.0% \n \n";
	}

	// Test clear() function
	public function testClear() {
		echo "TEST NAME: testClear \n \n";
		$cache = new CacheManager();
		$cache->set_overall_freq_cache(array("trivial_element"));
		$cache->set_search_freq_cache(array("trivial_element"));
		$cache->clear();
		$this->assertEmpty($cache->overall_freq_cache());
		$this->assertEmpty($cache->search_freq_cache());
		echo "PASS - Clear Validity Test : Asserts that Cache Frequency Arrays are Empty After Cache is Cleared\n";
		echo "Code Coverage : 4/58 statements = 5.2% | 0/4 branches = 0.0% \n \n";
	}

	// Test get_paper_list() function
	public function testGetPaperListSmall() {
		echo "TEST NAME: testGetPaperListSmall \n \n";
		$cache = new CacheManager();
		$input_one = array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "conference" => "Conference One", "data" => array("word" => 1));
		$input_two = array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "conference" => "Conference Two", "data" => array("word" => 2));
		$inputs = array($input_one, $input_two);
		$cache->set_search_freq_cache($inputs);
		$paper_list = $cache->get_paper_list("word");
		$this->assertSame($paper_list[0], array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "conference" => "Conference Two", "frequency" => 2));
		$this->assertSame($paper_list[1], array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "conference" => "Conference One", "frequency" => 1));
		echo "PASS - Cache Get Paper List Small Test : Asserts that the Paper List contains the same content, in the same order, as the wrapped inputs\n";
		echo "Code Coverage : 16/71 statements = 22.5% | 2/4 branches = 50.0% \n \n";
	}

	// Test get_paper_list() function
	public function testGetPaperListLarge() {
		echo "TEST NAME: testGetPaperListLarge \n \n";
		$cache = new CacheManager();
		$input_one = array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "conference" => "Conference One", "data" => array("word" => 1));
		$input_two = array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "conference" => "Conference Two", "data" => array("word" => 2));
		$input_three = array("path" => "3.pdf", "title" => "Title Three", "author" => "Author Three", "conference" => "Conference Three", "data" => array("word" => 3));
		$input_four = array("path" => "4.pdf", "title" => "Title Four", "author" => "Author Four", "conference" => "Conference Four", "data" => array("word" => 22));
		$input_five = array("path" => "5.pdf", "title" => "Title Five", "author" => "Author Five", "conference" => "Conference Five", "data" => array("word" => 58));
		$input_six = array("path" => "6.pdf", "title" => "Title Six", "author" => "Author Six", "conference" => "Conference Six", "data" => array("word" => 57));
		$inputs = array($input_one, $input_two, $input_three, $input_four, $input_five, $input_six);
		$cache->set_search_freq_cache($inputs);
		$paper_list = $cache->get_paper_list("word");
		$this->assertSame($paper_list[0], array("path" => "5.pdf", "title" => "Title Five", "author" => "Author Five", "conference" => "Conference Five", "frequency" => 58));
		$this->assertSame($paper_list[1], array("path" => "6.pdf", "title" => "Title Six", "author" => "Author Six", "conference" => "Conference Six", "frequency" => 57));
		$this->assertSame($paper_list[2], array("path" => "4.pdf", "title" => "Title Four", "author" => "Author Four", "conference" => "Conference Four", "frequency" => 22));
		$this->assertSame($paper_list[3], array("path" => "3.pdf", "title" => "Title Three", "author" => "Author Three", "conference" => "Conference Three", "frequency" => 3));
		$this->assertSame($paper_list[4], array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "conference" => "Conference Two", "frequency" => 2));
		$this->assertSame($paper_list[5], array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "conference" => "Conference One", "frequency" => 1));
		echo "PASS - Cache Get Paper List Large Test : Asserts that the Paper List contains the same content, in the same order, as the wrapped inputs\n";
		echo "Code Coverage : 16/71 statements = 22.5% | 2/4 branches = 50.0% \n \n";
	}

	// Test get_overall_frequencies() function
	public function testGetOverallFrequenciesSmall() {
		echo "TEST NAME: testGetOverallFrequenciesSmall \n \n";
		$cache = new CacheManager();
		$mock_arr = array();
		$input_one = array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "data" => array("word1" => 1, "word2" => 2));
		$input_two = array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "data" => array("word3" => 3, "word2" => 2));
		$inputs = array($mock_arr, array($input_one, $input_two));
		$cache->add_to_lifetime_cache("input_one", "cap_one", $inputs);
		$overall_freq_count = $cache->get_overall_frequencies("input_one cap_one");
		$this->assertSame($overall_freq_count[0]["key"], "word2");
		$this->assertSame($overall_freq_count[0]["value"], 4);
		$this->assertSame($overall_freq_count[1]["key"], "word3");
		$this->assertSame($overall_freq_count[1]["value"], 3);
		$this->assertSame($overall_freq_count[2]["key"], "word1");
		$this->assertSame($overall_freq_count[2]["value"], 1);
		echo "PASS - Cache Get Overall Frequencies Small Test : Asserts that Overall Frequencies contains all words from both input data, ordered by descending frequency\n";
		echo "Code Coverage : 22/71 statements = 31.0% | 2/4 branches = 50.0% \n \n";
	}

	// Test get_overall_frequencies() function
	public function testGetOverallFrequenciesLarge() {
		echo "TEST NAME: testGetOverallFrequenciesLarge \n \n";
		$cache = new CacheManager();
		$mock_arr = array();
		$input_one = array("path" => "1.pdf", "title" => "Title One", "author" => "Author One", "data" => array("word1" => 1, "word2" => 2));
		$input_two = array("path" => "2.pdf", "title" => "Title Two", "author" => "Author Two", "data" => array("word3" => 3, "word2" => 2));
		$input_three = array("path" => "3.pdf", "title" => "Title Three", "author" => "Author Three", "data" => array("word5" => 8, "word1" => 6));
		$input_four = array("path" => "4.pdf", "title" => "Title Four", "author" => "Author Four", "data" => array("word2" => 6, "word5" => 1));
		$input_five = array("path" => "5.pdf", "title" => "Title Five", "author" => "Author Five", "data" => array("word3" => 4, "word1" => 3));
		$inputs = array($mock_arr, array($input_one, $input_two, $input_three, $input_four, $input_five));
		$cache->add_to_lifetime_cache("input_one", "cap_one", $inputs);
		$overall_freq_count = $cache->get_overall_frequencies("input_one cap_one");
		$this->assertSame($overall_freq_count[0]["key"], "word1");
		$this->assertSame($overall_freq_count[0]["value"], 10);
		$this->assertSame($overall_freq_count[1]["key"], "word2");
		$this->assertSame($overall_freq_count[1]["value"], 10);
		$this->assertSame($overall_freq_count[2]["key"], "word5");
		$this->assertSame($overall_freq_count[2]["value"], 9);
		$this->assertSame($overall_freq_count[3]["key"], "word3");
		$this->assertSame($overall_freq_count[3]["value"], 7);
		echo "PASS - Cache Get Overall Frequencies Large Test : Asserts that Overall Frequencies contains all words from both input data, ordered by descending frequency\n";
		echo "Code Coverage : 22/71 statements = 31.0% | 2/4 branches = 50.0% \n \n";

		// Overall results
		echo "CACHE MANAGER TEST - OVERALL RESULTS\n";
		echo "Overall Code Coverage : 66/71 statements = 93.0% | 4/4 branches = 100.0% \n \n";
	}
}
?>
