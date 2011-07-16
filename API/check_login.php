<?php

include('API_common.php');

$username = $_GET['username'];
$hash = $_GET['hash'];

$result = login($username,$hash);
require_once("print_formatted_result.php");
print_formatted_result($result,$format,$callback);
/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function login($username,$hash) {

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$username = mysql_real_escape_string($username);
	$hash = mysql_real_escape_string($hash);

	$query = "SELECT * FROM users WHERE username = '$username' AND hash = '$hash';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	if (mysql_numrows($result)==1) {
		$row = mysql_fetch_array($result);
		$result = array("uid" => $row['user_id'], 'username'=>$row['username'],'hash'=>$row['hash']);	
		return $result;
	} else {
		return 'false';
	}
}

