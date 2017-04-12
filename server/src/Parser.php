<?php

require __DIR__ . '/../vendor/autoload.php';

final class Parser {
	# private path to all PDF's from the current context
	private $pdf_dir = __DIR__ . '/../../pdfs/';

	# private parser helper from PdfParser composer library
	private $pdf_parser;

	# private path to acm digital library root
	private $acm = 'http://dl.acm.org/citation.cfm?';

	# private helper arrays from which to remove elements from text
	private $symbols = array(",", ".", ";", "!", ")", "(", "/", "?", "\"", "'", "-", "*");
	private $conjunctions = array("i", "you", "he", "she", "we", "you", "they", "and", "but", "or", "yet", "for", "nor", "so", "it", "", "the", "its", "if", "at", "to", "too", "then",
	"them", "when", "ill", "ive", "got", "be", "been", "was", "of", "aint", "me", "is", "what", "from", "here", "there", "where", "will", "would", "uh", "my", "on", "that", "im", "in",
	"with", "dont", "your", "this", "some", "how", "oh", "about", "these", "are", "can", "still", "cant", "youre", "cant", "have", "why", "went", "yours", "as", "had", "went", "should", "maybe", "every",
	"tryna", "going", "whose", "myself", "yourself", "herself", "hisself", "a");

	# constructor for class
	public function __construct() {
		# init the parser
		$this->pdf_parser = new \Smalot\PdfParser\Parser();
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

			// # get pdf metadata (commented out for now)
			// $pdf_id = pathinfo($file, PATHINFO_FILENAME);
			// $pdf_metadata = $this->getMetadata($pdf_id);

			# get lowercase version of pure text from pdf
			$details = $pdf->getDetails();
			$title = (isset($details["Title"])) ? $details["Title"] : "No Title Found";
			$author = (isset($details["Author"])) ? $details["Author"] : "No Author Found";
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
			$entry = array("path" => $file, "title" => $title, "author" => $author, "data" => $paper_freq_count);
			$paper_freq_counts[] = $entry;
		} catch (Exception $e) {}
	}

	# function that simple gets metadata for a given pdf,
	# from that pdf's pdf_id (currently ACM-biased)
	public function getMetadata($pdf_id) {
		// empty for now
	}
}
?>
