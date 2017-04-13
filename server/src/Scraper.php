<?php
final class Scraper {
	# private path to scrapy directories from the current context
	private $acm_dir = __DIR__ . '/../../scrapyACM/';
	private $acm_abs_dir = __DIR__ . '/../../scrapyACMAbs/';
	private $ieee_dir = __DIR__ . '/../../scrapyIEEE/';
	private $ieee_abs_dir = __DIR__ . ''; // TODO: ieee abstract scraping

	# responsible for scraping for papers with input and cap
	public function scrapeForPapers($input, $cap) {
		# build and execute python command
		chdir($this->acm_dir);
		$command = "scrapy crawl scrapyACM -a search={$input} -a number={$cap}";
		exec($command);
	}

	# responsible for scraping for abstract given paper id
	public function scrapeForAbstract($id) {
		# build and execute python command
		chdir($this->acm_abs_dir);
		$command = "scrapy crawl scrapyACMAbs -a search={$id}";
		exec($command);
	}
}
?>
