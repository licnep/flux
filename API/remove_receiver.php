<?php

include('API_common.php');

//we get the specifics for the transaction from the GET values passed
$flux_from_id = $_GET['flux_from_id'];
$flux_to_id = $_GET['flux_to_id'];

$result = remove_receiver($flux_from_id,$flux_to_id);
require_once("print_formatted_result.php");

if ($result==1) $return = "SUCCESS";
else $return = "FAIL";
print_formatted_result($return,$format,$callback);

function remove_receiver($flux_from_id,$flux_to_id) {

	//TODO check login and ownership of the flux
	
	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$query = "DELETE FROM routing WHERE ".
			 " flux_from_id=".mysql_real_escape_string($flux_from_id).
			 " AND flux_to_id=".mysql_real_escape_string($flux_to_id);
	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	return $result;
}

?>
