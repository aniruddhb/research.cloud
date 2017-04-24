#! /bin/bash

echo "Begin Testing and Report Generation"

php phpunit.phar --bootstrap src/CacheManager.php tests/CacheManagerTests > cache_manager_report.txt
echo "Completed Cache Manager Report Generation"

php phpunit.phar --bootstrap src/Parser.php tests/ParserTests > parser_report.txt
echo "Completed Parser Report Generation"

php phpunit.phar tests/HighlightTests > highlight_report.txt
echo "Completed Highlight Report Generation"

php phpunit.phar --bootstrap src/Scraper.php tests/ScraperTests > scraper_report.txt
echo "Completed Scraper Report Generation"

echo "End Testing and Report Generation"
