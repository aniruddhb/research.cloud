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
	}
}
?>