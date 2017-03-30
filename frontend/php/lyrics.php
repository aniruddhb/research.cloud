<?php

function findTrackID($keyword, $track) {
  $result = file_get_contents("http://api.musixmatch.com/ws/1.1/track.search?q_track={$track}&q_arist={$keyword}&page_size=10&page=1&s_track_rating=desc&apikey=a820a7147e13aa7c816324dc7c2c57b9");

  $all_track_names = json_decode($result, true);
  $track_id = $all_track_names[message][body][track_list][0][track][track_id];

  return $track_id;
}

function findPaperAbstract($keyword, $track) {

  $track_id = findTrackID($keyword, $track);

  $result = file_get_contents("http://api.musixmatch.com/ws/1.1/track.abstract.get?track_id={$track_id}&apikey=a820a7147e13aa7c816324dc7c2c57b9");

  $abstract_json = json_decode($result, true);
  $abstract = $abstract_json[message][body][abstract][abstract_body];

  return $abstract;
}

function parsePaperAbstract($abstract, &$overall_freq) {
  // Convert string to lowercase
  $abstract = strtolower($abstract);

  // Remove symbols from abstract string
  $symbols_to_remove = array(",", ".", ";", "!", ")", "(", "/", "?", "\"", "'", "-", "*", "1", "2", "3", "4", "5", "6", "7", "8", "9", "0");

  $abstract = str_replace($symbols_to_remove, "", $abstract);
  $abstract = str_replace("\n", " ", $abstract);
  $abstract = substr($abstract, 0, -50);

  echo $abstract;

  $array_of_words = explode(" ", $abstract);
  // print_r($array_of_words);

  // Remove conjunctions
  $array_of_words = array_diff($array_of_words, ["i", "you", "he", "she", "we", "you", "they", "and", "but", "or", "yet", "for", "nor", "so"]);

  $frequency_counts = array();

  foreach ($array_of_words as $word) {
    if (array_key_exists($word, $array_of_words)) {
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

function parseAllAbstract(&$keyword_and_song_list, &$overall_freq) {
  $keyword_text = $keyword_and_song_list["keyword"];
  $song_list = $keyword_and_song_list["songs"];

  // Individual song frequency list
  $paper_frequency_list = array();

  foreach($song_list as $song) {
    $abstract = findPaperAbstract($keyword_text, $song);
    $individual_song_freq = parsePaperAbstract($abstract, $overall_freq);
    array_push($paper_frequency_list, $individual_song_freq);
  }

  arsort($overall_freq);
  print_r($overall_freq);
  print_r($paper_frequency_list);
}

$arr = array();
$arr["keyword"] = "justin+beiber";
$arr["songs"] = array("what+do+you+mean", "baby", "boyfriend", "never+say+never");

$overall_freq = array();
parseAllAbstract($arr, $overall_freq);

?>
