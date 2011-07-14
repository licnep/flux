<?php

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

$email = $_GET['email'];
$password = $_GET['password'];

$result = login($email,$password);
require_once("print_formatted_result.php");
$format='json';
print_formatted_result($result,$format);
/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function login($email,$password) {

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$email = mysql_real_escape_string($email);
	$hash = md5(md5($password).md5($email));

	$query = "SELECT * FROM users WHERE email = '$email' AND password = '$hash';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	if (mysql_numrows($result)==1) {
		$row = mysql_fetch_array($result);
		$result = array("uid" => $row['user_id'], "cookie" => $row['cookie']);	
		return $result;
	} else {
		return 'false';
	}
}

