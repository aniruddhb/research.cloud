<?php

# ROUTES

# In this file, we specify our application's HTTP routes
# and provide Closure callbacks to deal with user requests

# On landing route, store session-wide scraper and parser variable for future use
$app->get('/', function ($request, $response, $args) {
	# if scraper and parser don't already exist
	if (!isset($_SESSION['scraper']) && !isset($_SESSION['parser'])) {
		# create and serialize scraper, parser, and cachemanager objs into SESSION var
		$scraper = new Scraper();
		$parser = new Parser();
		$cache = new CacheManager();
		$_SESSION['scraper'] = serialize($scraper);
		$_SESSION['parser'] = serialize($parser);
		$_SESSION['cache'] = serialize($cache);
	}

	$new_res = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8081');
	return $new_res;
});

# On this route, perform all operations required to get
# word cloud information for the given search input and X cap
$app->get('/api/wordcloud/{search_input}/{search_cap}', function ($request, $response, $args) {
	# get scraper, parser, and cache from session
	$scraper = unserialize($_SESSION['scraper']);
	$parser = unserialize($_SESSION['parser']);
	$cache = unserialize($_SESSION['cache']);

	# get callback from req
	$callback = $request->getQueryParam('callback');

	# get query params
	$search_input = $args['search_input'];
	$search_cap = $args['search_cap'];

	# sanitize params
	# TODO: Figure out what param modifications Charlie and Sam need for the py script
	$search_input = str_replace(' ', '%20', trim($search_input));

	# check if we've had this search_input + search_cap combo before or not
	$results;
	if ($cache->contains($search_input . " " . $search_cap)) {
		# get results from lifetime cache
		$results = $cache->lifetime_freq_cache()[$search_input . " " . $search_cap];

		# add pieces of results array to respective caches
		$cache->set_overall_freq_cache($results[0]);
		$cache->set_search_freq_cache($results[1]);
	} else {
		# clear pdf dir of old search files
		array_map('unlink', glob(__DIR__ . '/../../pdfs/*'));

		# clear overall and search freq caches
		$cache->clear();

		# query scraper for papers with input and cap
		$scraper->scrapeForPapers($search_input, $search_cap);

		# PDF's are now saved in scrapyACM/pdf/ dir, query parser
		$results = $parser->parseAllResearchPapers();

		# add pieces of results array to respective caches
		$cache->set_overall_freq_cache($results[0]);
		$cache->set_search_freq_cache($results[1]);
		$cache->add_to_lifetime_cache(strtolower($search_input), $search_cap, $results);
	}

	# serialize cache back into session
	$_SESSION['cache'] = serialize($cache);

	# encode overall results as json to send restfully
	$overall_freq_formatted = json_encode($results[0]);

	# convert current response to jsonp callback with new response
	$new_res = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and results
	# write it to the body of the new response
	$callback = "{$callback}({$overall_freq_formatted})";
	$new_res->getBody()->write($callback);
	return $new_res;
});

# On this route, perform all operations required to get a list of
# papers for the selected word from the word cloud
$app->get('/api/papers/{word}', function ($request, $response, $args) {
	# get scraper, parser, and cache from session
	$scraper = unserialize($_SESSION['scraper']);
	$parser = unserialize($_SESSION['parser']);
	$cache = unserialize($_SESSION['cache']);

	# get callback from req
	$callback = $request->getQueryParam('callback');

	# get query params
	$word = $args['word'];

	# get list of papers in which this word occurs
	$papers_list = json_encode($cache->get_paper_list($word));

	# convert current response to jsonp callback with new response
	$new_res = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and results
	# write it to the body of the new response
	$callback = "{$callback}({$papers_list})";
	$new_res->getBody()->write($callback);
	return $new_res;
});

# On this route, perform all operations required to get a base64
# encoded representative download of the file request by the user
$app->get('/api/download', function ($request, $response, $args) {
	# get name of file from query param
	$file_name = $request->getQueryParam('id');

	# get and encode file into base64 stream
	$file = __DIR__ . '/../../pdfs/id=' . $file_name;
	$encoded_contents = base64_encode(file_get_contents($file));

	# return new, modified response
	$response->getBody()->write($encoded_contents);
	$new_res = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8081');
	return $new_res;
});

# On this route, perform all operations required to get
# abstract of a given paper
$app->get('/api/abstract', function ($request, $response, $args) {
	# get scraper from session
	$scraper = unserialize($_SESSION['scraper']);

	# get callback from req
	$callback = $request->getQueryParam('callback');

	# get and sanitize query param
	$paper_id = pathinfo($request->getQueryParam('id'), PATHINFO_FILENAME);

	# query scraper for abstract with paper_path
	$scraper->scrapeForAbstract($paper_id);

	# get abstract data from file in json format
	$json_data = file_get_contents(__DIR__ . '/../../scrapyACMAbs/abstract.json');

	# convert current response to jsonp callback with new response
	$new_res = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and results
	# write it to the body of the new response
	$callback = "{$callback}({$json_data})";
	$new_res->getBody()->write($callback);
	return $new_res;
});
