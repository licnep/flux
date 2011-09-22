<?php	
	//FOR DEBUG: (enable error reporting)
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);

	/*we include the print_formatted_result function because it's needed by all the api calls, so they don't have to include it in every file and can just call the print_formatted_result function*/
	require_once(dirname(__FILE__)."/print_formatted_result.php");
	/*the execute_query is also required by all calls that need to interact with the DB*/
	require_once(dirname(__FILE__)."/execute_query.php");
        /*sometimes we want to make api calls from the api itself, so let's include this too.*/
        /*this can also be used for calls to APIs on other domains, trough 'get_webpage($url)'*/
        require_once(dirname(__FILE__)."/phpAPI/phpAPI.php");

	
	//set the output format
	$format = 'json'; //default is json
	if (isset($_GET['format'])) {
		switch($_GET['format']) {
			case 'json': $format = 'json'; break;
			case 'xml': $format = 'xml'; break; //just an example, not working, we still gotta choose the other formats
		}
	}

	//set an optional callback function
	// useful for the javascript api (see: http://www.xml.com/pub/a/2005/12/21/json-dynamic-script-tag.html)
	$callback = '';
	if (isset($_GET['callback'])) $callback = $_GET['callback'];
?>
