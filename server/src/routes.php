<?php

# ROUTES

# In this file, we specify our application's HTTP routes 
# and provide Closure callbacks to deal with user requests

# On landing route, store session-wide Scraper variable for future use
$app->get('/', function ($request, $response, $args) {
	# if scraper and parser don't already exist
	if (!isset($_SESSION['scraper']) && !isset($_SESSION['parser'])) {
		# create and serialize scraper / parser objs into SESSION var
		$scraper = new Scraper();
		$parser = new Parser();
		$_SESSION['scraper'] = serialize($scraper);
		$_SESSION['parser'] = serialize($parser);
	}

	$new_res = $response->withHeader('Access-Control-Allow-Origin', 'http://localhost:8081');
	return $new_res;
});

# On this route, perform all operations required to get
# word cloud information for the given search input and X cap
$app->get('/api/wordcloud/{search_input}/{search_cap}', function ($request, $response, $args) {
	# get scraper and parser from session
	$scraper = unserialize($_SESSION['scraper']);
	$parser = unserialize($_SESSION['parser']);

	# get callback from req
	$callback = $request->getQueryParam('callback');

	# get query params
	$search_input = $args['search_input'];
	$search_cap = $args['search_cap'];

	# sanitize params
	# TODO: Figure out what param modifications Charlie and Sam need for the py script
	$search_input = str_replace(' ', '%20', trim($search_input));

	# query scraper for papers with input and cap
	$scraper->scrapeForPapers($search_input, $search_cap);

	# PDF's are now saved in /pdf/ dir, query parsehelper
	$results = $parser->parseResearchPapers();

	# convert current response to jsonp callback with new response
	$new_res = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and results 
	# write it to the body of the new response
	$callback = "{$callback}({$results})";
	$new_res->getBody()->write($callback);
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
});

# On this route, perform all operations to get the abstract of 
# a song written by a particular keyword, and return these abstract
# to the frontend
$app->get('/api/abstract/{keyword}/{song}', function ($request, $response, $args) {
	# get managers from session
	$api = unserialize($_SESSION['api']);
	$cache = unserialize($_SESSION['cache']);

	# get query params for jsonp callback
	$callback = $request->getQueryParam('callback');

	# get params and sanitize song name
	$keyword = $args['keyword'];
	$song = $args['song'];
	$song = str_replace(' ','-', $song);

	# query api through manager
	$track_id = $api->get_track_id($keyword, $song);
	$abstract = $api->get_abstract($track_id);
	$abstract = json_encode($abstract);

	# convert current response to jsonp callback with new response
	$new_response = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and abstract data
	# write it to the body of the new response and return
	$callback = "{$callback}({$abstract})";
	$new_response->getBody()->write($callback);
	return $new_response;
});

# On this route, perform all operations to get the list of songs
# that a particular word appears in, as well as the chosen word's
# frequency within each song
$app->get('/api/paperList/{word}', function ($request, $response, $args) {
	# get managers from session
	$api = unserialize($_SESSION['api']);
	$cache = unserialize($_SESSION['cache']);

	# get query params for jsonp callback
	$callback = $request->getQueryParam('callback');

	# get param
	$word = $args['word'];

	# query api through manager
	$song_list = $api->get_song_list($word, $cache->search_freq_cache());
	$song_list = json_encode($song_list);

	# convert current response to jsonp callback with new response
	$new_response = $response->withHeader('Content-Type', 'application/javascript');

	# create string with callback and abstract data
	# write it to the body of the new response and return
	$callback = "{$callback}({$song_list})";
	$new_response->getBody()->write($callback);
	return $new_response;
});