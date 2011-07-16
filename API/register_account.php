<?php

include('API_common.php');

$username = $_GET['username'];
$password = $_GET['password'];

$result = create_account($username,$password);
require_once("print_formatted_result.php");
print_formatted_result($result,$format,$callback);

/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function create_account($username,$password) {

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$username = mysql_real_escape_string($username);
	$hash = md5(md5($password).md5($username));

	$query = "INSERT INTO users SET ".
			" username='$username', hash = '$hash';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	return $result;
}

