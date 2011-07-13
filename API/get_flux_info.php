<?php

//FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

$flux_id = $_GET['flux_id'];
if (isset($_GET['format'])) $format = $_GET['format'];
else $format = "json"; //default format is json

if (isset($_GET['callback'])) $callback = $_GET['callback'];
else $callback = '';

$result = get_flux_info($flux_id,$format);
$rows = array();
while($r = mysql_fetch_assoc($result)) {
	array_push($rows,$r);
}

require_once("print_formatted_result.php");
print_formatted_result($rows,$format,$callback);

function get_flux_info($flux_id,$format = "json") {
	require_once('execute_query.php');
	$db = db_connect("flux_changer");
	$query = "SELECT flux_from_id, flux_to_id, share FROM
		fluxes, routing
		WHERE fluxes.flux_id='".mysql_real_escape_string($flux_id).
		"' AND routing.flux_from_id = fluxes.flux_id";
	$query = "SELECT flux_from_id, flux_to_id, share, name, description
			FROM fluxes, routing
			WHERE routing.flux_from_id='".mysql_real_escape_string($flux_id).
			"' AND routing.flux_to_id=fluxes.flux_id";
	$result = mysql_query($query,$db);
	if(!$result) {//TODO something
		die("query failed, query: ".$query."\n error:".mysql_error());
	}
	return $result;

}
?>
