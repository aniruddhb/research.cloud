<?php

require __DIR__ . '/../vendor/autoload.php';

final class Parser {
	# private path to all PDF's from the current context
	private $pdf_dir;

	# private pdf parser helper from PdfParser composer library
	private $pdf_parser;

	# private helper arrays from which to remove elements from text
	private $symbols = array(",", ".", ";", "!", ")", "(", "/", "?", "\"", "'", "-", "*");
	private $conjunctions = array("i", "you", "he", "she", "we", "you", "they", "and", "but", "or", "yet", "for", "nor", "so", "it", "", "the", "its", "if", "at", "to", "too", "then",
	"them", "when", "ill", "ive", "got", "be", "been", "was", "of", "aint", "me", "is", "what", "from", "here", "there", "where", "will", "would", "uh", "my", "on", "that", "im", "in",
	"with", "dont", "your", "this", "some", "how", "oh", "about", "these", "are", "can", "still", "cant", "youre", "cant", "have", "why", "went", "yours", "as", "had", "went", "should", "maybe", "every",
	"tryna", "going", "whose", "myself", "yourself", "herself", "hisself", "a");

	# constructor for class
	public function __construct($dir) {
		# init the pdf parser
		$this->pdf_parser = new \Smalot\PdfParser\Parser();

		# init pdf_dir
		$this->pdf_dir = $dir;
	}

	# function that parses and generates wordcloud
	# from all PDF's inside $pdf_dir
	public function parseAllResearchPapers() {
		# init array to hold total wordcloud frequency count
		$overall_freq_count = array();

		# init array to hold paper-specific frequency counts
		$paper_freq_counts = array();

		# looks through all PDF's and parses each PDF
		# into an aggregate word cloud of size 250
		$files = scandir($this->pdf_dir);
		$files_len = count($files);
		for ($i = 2; $i < $files_len; $i++) {
			$this->parseResearchPaper($files[$i], $overall_freq_count, $paper_freq_counts);
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

		# cull size of formatted results to 250 or less
		$formatted = (sizeof($formatted) >= 250) ? array_slice($formatted, 0, 250) : $formatted;

		# return array tuple of formatted results and individual freq counts per paper
		return array($formatted, $paper_freq_counts);
	}

	# function that parses individual research paper
	# and adds to overall frequency count array
	public function parseResearchPaper($file, &$overall_freq_count, &$paper_freq_counts) {
		# wrap with try catch incase we can't parse one of the pdf's
		try {
			# get pdf abstraction of file using pdf_parser
			$pdf = $this->pdf_parser->parseFile($this->pdf_dir . $file);

			# get paper id and filter for metadata using it
			$paper_id = str_replace(".pdf", "", str_replace("id=", "", $file));
			$details = json_decode(file_get_contents(__DIR__ . '/../../scrapyACM/metadata.json'), true);
			$details_entry = array_filter($details, function($elem) use ($paper_id){
				return strcmp($elem["paperID"], $paper_id) == 0;
			});
			$details_entry = $details_entry[array_keys($details_entry)[0]];

			# get metadata from $details_entry
			$title = $details_entry["title"];
			$authors = $details_entry["authors"];
			$author_text = "";
			foreach ($authors as $author) {
				$author_text .= ($author . ", ");
			}
			$author_text = substr($author_text, 0, -2);
			$conference = $details_entry["conference"];

			# get lowercase version of pure text from pdf
			$text = strtolower($pdf->getText());

			# sanitize text with removals / replacements
			$text = str_replace($this->symbols, "", $text);
			$text = str_replace("\n", " ", $text);

			# get array of words from text and remove banned-words
			$array_of_words = explode(" ", $text);
			$array_of_words = array_diff($array_of_words, $this->conjunctions);

			# compute frequency counts from array of words
			$paper_freq_count = array();
			foreach ($array_of_words as $word) {
				# only include words that are fully alphabetic
				if (ctype_alpha($word) && strlen($word) > 3) {
					# fill paper freq count with word or init for that word
					if (array_key_exists($word, $paper_freq_count)) {
						$paper_freq_count[$word]++;
					} else {
						$paper_freq_count[$word] = 1;
					}

					# fill overall freq count with word or init for that word
					if (array_key_exists($word, $overall_freq_count)) {
						$overall_freq_count[$word]++;
					} else {
						$overall_freq_count[$word] = 1;
					}
				}
			}

			# add paper-specific count to total array with structured entry
			$entry = array("path" => $file, "title" => $title, "author" => $author_text, "conference" => $conference, "data" => $paper_freq_count);
			$paper_freq_counts[$file] = $entry;
		} catch (Exception $e) {}
	}
}
?>
