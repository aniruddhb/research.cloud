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
		array_map('unlink', glob(__DIR__ . '/../../scrapyACM/pdf/*'));

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

# On this route, perform all operations required to get a blob
# binary download of the file request by the user
$app->get('/api/download/{file}', function ($request, $response, $args) {
	# get callback from req
	$callback = $request->getQueryParam('callback');

	# convert current response to jsonp callback with new response
	$file = __DIR__ . '/../pdf/' . $args['file'];
	$new_res = $res->withHeader('Content-Description', 'File Transfer')
   				   ->withHeader('Content-Type', 'application/octet-stream')
				   ->withHeader('Content-Disposition', 'attachment;filename="'.basename($file).'"')
				   ->withHeader('Expires', '0')
				   ->withHeader('Cache-Control', 'must-revalidate')
				   ->withHeader('Pragma', 'public')
				   ->withHeader('Content-Length', filesize($file));
	readfile($file);
	return $new_res;
});

# On this route, perform all operations required to get
# the abstract of a given paper
$app->get('/api/abstract/{paper_id}', function ($request, $response, $args) {
	# get scraper and parser from session
	$scraper = unserialize($_SESSION['scraper']);
	$parser = unserialize($_SESSION['parser']);

	# get callback from req
	$callback = $request->getQueryParam('callback');

	# get query param
	$paper_id = $args['paper_id'];

	# query scraper for abstract with paper_id
	$abstract = $scraper->scrapeForAbstract($paper_id);

	# convert current response to jsonp callback with new response
	$new_res = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and results
	# write it to the body of the new response
	$callback = "{$callback}({$abstract})";
	$new_res->getBody()->write($callback);
	return $new_res;
});
