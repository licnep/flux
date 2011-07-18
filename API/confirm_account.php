<?php
/*
 *            CURRENTLY NEVER USED. DEPRECATED
 */

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

$user_id = $_GET['user_id'];
$confirmation_code = $_GET['confirmation_code'];
$redirect = $_GET['redirect'];

registration_confirm($user_id,$confirmation_code);
header( 'Location: '.$redirect );

/**
 * Activate the account with the confirmation code
 */
function registration_confirm($user_id,$confirmation_code) {
	
	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$query = "UPDATE users SET confirmed = 1".
			" WHERE user_id='".$user_id."';";

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
}

