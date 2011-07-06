<?php
/*
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *                    Version 2, December 2004
 * 
 * Copyright (C) 2011 Alessandro Preziosi <lsnpreziosi+f@gmail.com>
 * Everyone is permitted to copy and distribute verbatim or modified
 * copies of this document, and changing it is allowed as long as the 
 * name is changed.
 *
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
 * 
 *  0. You just DO WHAT THE FUCK YOU WANT TO.
 *
 * ===================================================================
 *  "I can't claim ownership over this document.
 *   I did not invent the language I'm speaking, the lamp lighting my 
 *   desk, or the computer I'm typing this on.
 *   We are dwarfs sitting on the shoulders of giants.
 *   It's our turn now. BE THE GIANT."
 */

//FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

//get the user_id (you can request this info for any user)
$user_id = $_GET['user_id'];

//set the output format
$format = 'json';
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

$result = get_fluxes_owned_by($user_id);
print_formatted_result($result,$format,$callback);

function get_fluxes_owned_by($user_id) {
	require_once('execute_query.php');
	$db = db_connect("flux_changer");
	$query = "SELECT flux_id, name FROM
		fluxes
		WHERE owner='".mysql_real_escape_string($user_id)."';";
	$result = mysql_query($query,$db);
	if(!$result) {//TODO something
		die("query failed, query: ".$query."\n error:".mysql_error());
	}
	/*while($row = mysql_fetch_array($result)) {
		echo "Name: ".$row['name']." ID: ".$row['flux_id'].'<br/>';
	}*/
	return $result;

}


function print_formatted_result($result,$format,$callback="") {
	//JSON:
	if ($format=="json") {
		$rows = array();
		while($r = mysql_fetch_assoc($result)) {
			array_push($rows,$r);
		}
		//if callback is set, we return a valid javascript statement
		//otherwise just the plain json data
		if ($callback!='') echo $callback."(".json_encode($rows).");";
		else echo json_encode($rows);
	}
	//TODO other formats
}

?>
