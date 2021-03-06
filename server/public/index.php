<?php
# To help the built-in PHP dev server, check if the request was actually for
# something which should probably be served as a static file
if (PHP_SAPI == 'cli-server') {
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

# Set up composer autoloader
require __DIR__ . '/../vendor/autoload.php';

# Pull Scraper, Parser, and CacheManager into file 
# to be instantiated within /../src/routes.php
require __DIR__ . '/../src/Scraper.php';
require __DIR__ . '/../src/Parser.php';
require __DIR__ . '/../src/CacheManager.php';

# Now that we've pulled in our helpers, start a session
session_start();

# Instantiate the app with settings
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

# Register routes
require __DIR__ . '/../src/routes.php';

# Run app
$app->run();
