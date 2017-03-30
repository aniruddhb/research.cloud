<?php
class Scraper {
	# private path to scraping utility
	private $scraper_path = '/../utils/scraper.py';

	# responsible for scraping for papers with input and cap
	public function scrapeForPapers($input, $cap) {
		# build and execute python command
		$command = "python " . $this->scraper_path . " papers {$input} {$cap}";
		exec($command);
	}

	# responsible for scraping for abstract with paper id
	public function scrapeForAbstract($id) {
		# build and execute python command
		$command = "python " . $this->scraper_path . " abstract {$id}";
		$abstract = exec($command);
		return $abstract;
	}
}
?>