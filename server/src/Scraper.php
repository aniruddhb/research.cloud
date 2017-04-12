<?php
class Scraper {
	# private path to scrapy directories from the current context
	private $acm_dir = __DIR__ . '/../../scrapyACM/';

	# responsible for scraping for papers with input and cap
	public function scrapeForPapers($input, $cap) {
		# build and execute python command
		chdir($this->acm_dir);
		$command = "scrapy crawl scrapyACM -a search={$input} -a number={$cap}";
		exec($command);
	}

	# responsible for scraping for abstract with paper id
	public function scrapeForAbstract($id) {
		# return abstract
		# TODO: run python command to get abstract
		$abstract = "";
		return $abstract;
	}
}
?>
