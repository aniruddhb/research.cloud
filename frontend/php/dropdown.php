<?php

// Helper functions for dropdownkeywords

function checkImageURL($array, $x) {
	if(isset($array[$x]["images"][$x]["url"]))
    		return $array[$x]["images"][$x]["url"];
    	else
    		return "";
}

function checkkeywordName($array, $x) {
	if(isset($array[$x]["name"]))
    		return $array[$x]["name"];
    	else
    		return "";
}

function checkkeywordID($array, $x) {
	if(isset($array[$x]["id"]))
    		return $array[$x]["id"];
    	else
    		return "";
}

// Pass in keyword name you'd like dropdown for
// Function will return JSON with the following format (max 5 keywords):

/*
	{  
	   "name":[  
	      "Drake",
	      "Drake",
	      "Drake White",
	      "Nick Drake",
	      "Drake Bell"
	   ],
	   "keywordid":[  
	      "3TVXtAsR1Inumwj472S9r4",
	      "4W9G3Vnt9eXWTo4VeOQkSa",
	      "29ijED2bnnprp2TciAK1aO",
	      "5c3GLXai8YOMid29ZEuR9y",
	      "03ilIKH0i08IxmjKcn63ne"
	   ],
	   "image":[  
	      "https:\/\/i.scdn.co\/image\/cb080366dc8af1fe4dc90c4b9959794794884c66",
	      "https:\/\/i.scdn.co\/image\/f4a465c6022a30ee187452f7923e509d480c4c1a",
	      "https:\/\/i.scdn.co\/image\/8b7d34461462466d5a5b32d9d7a3a94729767c13",
	      "https:\/\/i.scdn.co\/image\/267080662cf3c019ea8020a4e0e8dd5a7be4d909",
	      ""
	   ]
	}
*/

function dropdownkeywords($aname) {

	$content = file_get_contents("https://api.spotify.com/v1/search?q=" . $aname . "&type=keyword&limit=5");
	$array=json_decode($content, true);

	$rtrnJSON = new stdClass();
	$rtrnJSON->name= array(checkkeywordName($array["keywords"]["items"], 0),
						   checkkeywordName($array["keywords"]["items"], 1),
						   checkkeywordName($array["keywords"]["items"], 2),
						   checkkeywordName($array["keywords"]["items"], 3),
						   checkkeywordName($array["keywords"]["items"], 4));
	$rtrnJSON->keywordid= array(checkkeywordID($array["keywords"]["items"], 0),
						   	   checkkeywordID($array["keywords"]["items"], 1),
						   	   checkkeywordID($array["keywords"]["items"], 2),
						       checkkeywordID($array["keywords"]["items"], 3),
						       checkkeywordID($array["keywords"]["items"], 4));
	$rtrnJSON->image= array(checkImageURL($array["keywords"]["items"], 0),
						    checkImageURL($array["keywords"]["items"], 1),
						    checkImageURL($array["keywords"]["items"], 2),
						    checkImageURL($array["keywords"]["items"], 3),
						    checkImageURL($array["keywords"]["items"], 4));
	
	return json_encode($rtrnJSON);

}

?>