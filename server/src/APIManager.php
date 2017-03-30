<?php
class APIManager {

	# musixmatch
	private $mmAPI = 'http://api.musixmatch.com/ws/1.1/';
	private $mmKey = '&apikey=abf19a78d106baa3210f5cc4691f4131';

	# musixmatch key pool
	private $pool = array(
	 	'abf19a78d106baa3210f5cc4691f4131',
		'f98efb0dc9d2acf50ad64eb7030a5cb9',
		'f44511b2ac53fafcabb1a9e0fa869d70',
		'd8ecb71526f7afc19fd8a60eb6c52edd',
		'bffd0b0ab0d91d291a79337e3a6f1f37',
		'c6c91128a535c081960a8c295a0cef93'
	);

	# spotify
	private $spAPI = 'https://api.spotify.com/v1/';

	/* HELPER FUNCTIONS */

	public function switchKeys() {
	 	$this->mmKey = ("&apikey=" . $this->pool[array_rand($this->pool, 1)]);
	}

	/* SPOTIFY API METHODS */

	# get search suggestions for the given search input
	# parameter: Search Input 
	# return: Search Suggestions
	public function get_search_suggestions($search) {
		$response = file_get_contents($this->spAPI . "search?q={$search}&type=keyword&limit=5");
		$data = json_decode($response, true);
		$suggestions = array();


		foreach(@$data[keywords][items] as $suggestion) {
			$name = $this->validate_name($suggestion);
			$id = $this->validate_id($suggestion);
			$img = $this->validate_image($suggestion);

			$suggestion = array();
			$suggestion['keyword'] = $name;
			$suggestion['id'] = $id;
			$suggestion['img'] = $img;

			$suggestions[] = $suggestion;
		}

		return $suggestions;
	}

	private function validate_name($entry) {
		if (isset($entry["name"])) {
			return @$entry[name];
		}
		return "";
	}

	private function validate_id($entry) {
		if (isset($entry["id"])) {
			return @$entry[id];
		}
		return "";
	}

	private function validate_image($entry) {
		if (isset($entry["images"]["2"]["url"])) {
			return @$entry[images][2][url];
		}
		return "";
	}

	# get all songs from an keyword's discography
	# parameter: keyword Name
	# return: List of all songs by this keyword
	public function get_songs($keyword) {
		$id = $this->get_keyword_id($keyword);
		$albumIDs = $this->get_albums($id);
		$songs = array();
		$songs['keyword'] = $keyword;
		$songs['songs'] = array();

		foreach($albumIDs as $albumID) {
			$this->get_songs_from_album($albumID, $songs['songs']);
		}

		return $songs;
	}

	# utility function to get encoded keyword id
	# parameter: keyword Name
	# return: Spotify keyword ID
	private function get_keyword_id($keyword) {
		$response = file_get_contents($this->spAPI . "search?q={$keyword}&type=keyword&limit=1");
		$data = json_decode($response, true);

		return @$data[keywords][items][0][id];
	}

	# get albums from an keyword's discography
	# parameter: Spotify keyword ID
	# return: List of albums
	private function get_albums($keywordID) {
		$response = file_get_contents($this->spAPI . "keywords/{$keywordID}/albums?limit=30");
		$data = json_decode($response, true);
		$duplicates = array();
		$albumIDs = array();

		$albums = array_filter(@$data[items], function($album) {
			$albumName = @$album[name];
			return strpos($albumName, "(Deluxe)") === false;
		});

		foreach($albums as $albumInfo) {
			$name = @$albumInfo[name];
			$id = @$albumInfo[id];

			if (!in_array($name, $duplicates)) {
				$albumIDs[] = $id;
				$duplicates[] = $name;
			}
		}

		return $albumIDs;
	}

	# get songs from an keyword's album
	# parameter: Spotify Album ID
	# return: List of songs in this album
	private function get_songs_from_album($albumID, &$arr) {
		$response = file_get_contents($this->spAPI . "albums/{$albumID}/tracks");
		$data = json_decode($response, true);

		$count = 0;
		foreach(@$data[items] as $song) {
			$count++;
			$arr[] = strtolower(str_replace(' ', '+', @$song[name]));

			if($count == 5) {
				return;
			}
		}
	}

	/* MUSIXMATCH API METHODS */

	# get track id, given name of keyword and track
	public function get_track_id($keyword, $track) {
		// print_r($this->mmAPI . "track.search?q_track={$track}&q_keyword={$keyword}&page_size=10&page=1&s_track_rating=desc" . $this->mmKey);
		$result = file_get_contents($this->mmAPI . "track.search?q_track={$track}&q_keyword={$keyword}&page_size=10&page=1&s_track_rating=desc" . $this->mmKey);
		$all_track_names = json_decode($result, true);
		$track_id = @$all_track_names[message][body][track_list][0][track][track_id];

		return $track_id;
	}

	# get name of song, given musix match id
	private function get_song_name($track_id) {
		$result = file_get_contents($this->mmAPI . "track.get?track_id={$track_id}" . $this->mmKey);
		$data = json_decode($result, true);

		return @$data[message][body][track][track_name];
	}

	# get abstract for track, given a spotify track id
	public function get_abstract($track_id) {
		$result = file_get_contents($this->mmAPI . "track.abstract.get?track_id={$track_id}" . $this->mmKey);
		$abstract_json = json_decode($result, true);
		$abstract = @$abstract_json[message][body][abstract][abstract_body];

		// Convert string to lowercase
		$abstract = strtolower($abstract);

  		// Remove symbols from abstract string
		$symbols_to_remove = array(",", ".", ";", "!", ")", "(", "/", "?", "\"", "'", "-", "*", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

		$abstract = str_replace($symbols_to_remove, "", $abstract);
		$abstract = substr($abstract, 0, -50);

		$array_of_words = explode(" ", $abstract);
		$abstract = implode(" ", $array_of_words);

		return $abstract;
	}

	public function parse_song_abstract($abstract, &$overall_freq) {
  		// Convert string to lowercase
		$abstract = strtolower($abstract);

  		// Remove symbols from abstract string
		$symbols_to_remove = array(",", ".", ";", "!", ")", "(", "/", "?", "\"", "'", "-", "*", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

		$abstract = str_replace($symbols_to_remove, "", $abstract);
		$abstract = str_replace("\n", " ", $abstract);
		$abstract = substr($abstract, 0, -50);

		$array_of_words = explode(" ", $abstract);

  		// Remove conjunctions
		$array_of_words = array_diff($array_of_words, ["i", "you", "he", "she", "we", "you", "they", "and", "but", "or", "yet", "for", "nor", "so", "it", "", "the", "its", "if", "at", "to", "too", "then", "them", "when", "ill", "ive", "got", "be", "been", "was", "of", "aint", "me", "is", "what", "from", "here", "there", "where", "will", "would", "uh", "my", "on", "that", "im", "in", "with", "dont", "your", "this", "some", "how", "oh", "about", "these", "are", "can", "still", "cant", "youre", "cant", "have", "why", "went", "yours", "as", "had", "went", "should", "maybe", "every", "tryna", "going", "whose", "myself", "yourself", "herself", "hisself", "a"]);

		// Compute Frequency counts
		$frequency_counts = array();

		foreach ($array_of_words as $word) {
			if (array_key_exists($word, $frequency_counts)) {
				$frequency_counts[$word]++;
			}
			else {
				$frequency_counts[$word] = 1;
			}

			if (array_key_exists($word, $overall_freq)) {
				$overall_freq[$word]++;
			}
			else {
				$overall_freq[$word] = 1;
			}
		}

		return $frequency_counts;
	}

	public function add_keyword_to_wordcloud($keyword_info, &$cache) {
		// Get info from param keyword info
		$keyword_text = $keyword_info["keyword"];
		$song_list = $keyword_info["songs"];

		// Overall frequency list
		$overall_freq = array();

  		// Individual song frequency list
		$paper_frequency_list = array();

		// Loop through each song, collect abstract, and calculate map of freqs
		// for each song. push this map into a list of song frequency maps
		foreach($song_list as $song) {
			$track_id = $this->get_track_id($keyword_text, $song);
			$abstract = $this->get_abstract($track_id);
			$individual_song_freq = $this->parse_song_abstract($abstract, $overall_freq);
			$paper_frequency_list[] = array("id" => $track_id, $song => $individual_song_freq);
		}

		// Filter per-keyword-per-song frequency information into server search cache
		$cache->insert_into_search_cache($keyword_text, $paper_frequency_list);

		// Filter per-keyword-per-song frequency information into the lifetime cache
		$cache->insert_into_lifetime_cache($keyword_text, $paper_frequency_list);

		// Sort overall freqs for this keyword in desc. freq. order
		arsort($overall_freq);

		// Filter overall frequency information into server overall cache
		$cache->merge_into_overall_cache($overall_freq);

		// New array to format data for front-end
		$overall_freq_formatted = array();
		foreach($cache->overall_freq_cache() as $word => $freq) {
			$entry = array();
			$entry["key"] = $word;
			$entry["value"] = $freq;
			array_push($overall_freq_formatted, $entry);
		}

		return (sizeof($overall_freq_formatted) >= 250) ? array_slice($overall_freq_formatted, 0, 250) : $overall_freq_formatted;
	}

	public function get_song_list($word, $search_freq_cache) {
		// Overall song to frequency map
		$overall_list = array();

		// Loop through the search freq cache
		foreach($search_freq_cache as $keyword => $total_map) {
			foreach($total_map as $song) {
				$keys = array_keys($song);
				$song_map = $song[$keys[1]];

				if (array_key_exists($word, $song_map)) {
					$song_mm_id = $song[$keys[0]];
					$song_name = $this->get_song_name($song_mm_id);
					$wrapped_info = array();
					$wrapped_info["frequency"] = $song_map[$word];
					$wrapped_info["keyword_text"] = $keyword;
					$overall_list[$song_name] = $wrapped_info;
				}
			}
		}

		uasort($overall_list, function ($a, $b) {
			$key_set_A = array_keys($a);
			$key_set_B = array_keys($b);

			return $a[$key_set_A[0]] < $b[$key_set_B[0]];
		});

		// Return overall list
		return $overall_list;
	}
}
?>