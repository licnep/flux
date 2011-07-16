<?php	
	//FOR DEBUG: (enable error reporting)
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);

	
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
