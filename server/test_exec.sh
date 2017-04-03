#! /bin/bash

echo "Begin Testing and Report Generation"

php phpunit.phar --bootstrap src/CacheManager.php tests/CacheManagerTests > cache_manager_report.txt
echo "Completed Cache Manager Report Generation"

php phpunit.phar --bootstrap src/Parser.php tests/ParserTests > parser_report.txt
echo "Completed Parser Report Generation"

echo "End Testing and Report Generation"