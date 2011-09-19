<?php

include(dirname(__FILE__).'/../API_common.php');
//check the api key, if it's wrong log out
include(dirname(__FILE__).'/../check_API_key.php');

$pool_id = $_GET['pool_id'];

//generate the transaction key
$transaction_id = 'TRANSACTION_KEY124';
//insert it into the database
require_once(dirname(__FILE__) . '/../execute_query.php');
$db = db_connect("flux_changer");
$query = "INSERT INTO transactions SET transaction_id='".mysql_real_escape_string($transaction_id).
		"', user_id='".mysql_real_escape_string($_USER['uid'])."'"; 
$result = mysql_query($query,$db);
if(!$result) {
    //query failed
	//TODO do something here
    die("query failed, query: ".$query."\n error:".mysql_error());
}

//print the result
print_formatted_result($transaction_id,$format,$callback);

?>
