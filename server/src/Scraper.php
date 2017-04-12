<?php
class Scraper {
	# responsible for scraping for papers with input and cap
	public function scrapeForPapers($input, $cap) {
		# build and execute python command
		chdir("/Users/catherinechung/Documents/cs310/research.cloud/scrapyACM/");
		$command = "gtimeout --signal=KILL 10 scrapy crawl scrapyACM -a search={$input} -a number={$cap}";
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
