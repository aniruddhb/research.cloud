<?php
final class CacheManager {

	# holds overall freq cache (per currently searched phrase(s))
	private $overall_freq_cache = array();

	# holds search freq cache (per-search per-paper)
	private $search_freq_cache = array();

	# holds lifetime freq cache (total lifetime of server)
	private $lifetime_freq_cache = array();

	# constructor
	public function __construct() {}

	# accessor helper function to access overall cache
	public function overall_freq_cache() {
		return $this->overall_freq_cache;
	}

	# accessor helper function to set overall cache
	public function set_overall_freq_cache($overall_freq_cache) {
		$this->overall_freq_cache = $overall_freq_cache;
	}

	# accessor helper function to access search cache
	public function search_freq_cache() {
		return $this->search_freq_cache;
	}

	# accessor helper function to set search cache
	public function set_search_freq_cache($search_freq_cache) {
		$this->search_freq_cache = $search_freq_cache;
	}

	# accessor helper function to access lifetime cache
	public function lifetime_freq_cache() {
		return $this->lifetime_freq_cache;
	}

	# accessor helper function to add to lifetime cache
	public function add_to_lifetime_cache($search_input, $search_cap, $lifetime_freq_cache) {
		$this->lifetime_freq_cache[$search_input . " " . $search_cap] = $lifetime_freq_cache;
	}

	# does the lifetime cache contain the searched phase(s)?
	public function contains($search_phrase) {
		return array_key_exists($search_phrase, $this->lifetime_freq_cache);
	}

	# for a given word, get a formatted list of papers containing the word, 
	# and the frequency of the word in that paper
	public function get_paper_list($word) {
		# overall paper to word frequency map
		$overall_papers_list = array();

		# loop through current search cache to find this word in each paper
		foreach ($this->search_freq_cache as $cache_entry) {
			if (array_key_exists($word, $cache_entry["data"])) {
				$paper_word_occurrence_info = array("path" => $cache_entry["path"], 
													"title" => $cache_entry["title"],
													"frequency" => $cache_entry["data"][$word]
													);
				$overall_papers_list[] = $paper_word_occurrence_info;
			}
		}

		# sort by descending order of word frequency
		uasort($overall_papers_list, function($entry_one, $entry_two) {
			return $entry_one["frequency"] < $entry_two["frequency"];
		});

		# return papers list
		return $overall_papers_list;
	}

	# takes a searched phrase and outputs an overall frequency map
	# for the phrase, based on cached information over server lifetime
	public function get_overall_frequencies($search_phrase) {
		# get per-search per-paper list using param phrase
		$search_paper_list = $this->lifetime_freq_cache[$search_phrase][1];

		# overall word to frequency list
		$overall_freq_count = array();

		# iterate through search_paper_list
		foreach ($search_paper_list as $paper_freq_count) {
			foreach ($paper_freq_count["data"] as $word => $count) {
				if (array_key_exists($word, $overall_freq_count)) {
					$overall_freq_count[$word] += $count;
				} else {
					$overall_freq_count[$word] = $count;
				}
			}
		}

		# sort overall frequency count
		arsort($overall_freq_count);

		# format overall_freq_count into front-end usable format
		# (key => word, value => count) format
		$formatted = array();
		foreach ($overall_freq_count as $word => $count) {
			$entry = array();
			$entry["key"] = $word;
			$entry["value"] = $count;
			$formatted[] = $entry;
		}

		# return formatted, size <= 250 list
		return (sizeof($formatted) >= 250) ? array_slice($formatted, 0, 250) : $formatted;
	}

	# clear out the cache
	public function clear() {
		# reinstantiate overall and search cache, not lifetime cache!
		$this->overall_freq_cache = array();
		$this->search_freq_cache = array();
	}
}
?>