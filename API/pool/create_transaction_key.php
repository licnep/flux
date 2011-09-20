<?php

include(dirname(__FILE__).'/../API_common.php');
//check the api key, if it's wrong log out
include(dirname(__FILE__).'/../check_API_key.php');

$pool_id = $_GET['pool_id'];

//generate the unique transaction id:
$transaction_id = uniqid(); 

//insert it into the database
require_once(dirname(__FILE__) . '/../execute_query.php');
$db = db_connect("flux_changer");
$query = "INSERT INTO transactions SET transaction_id='".$transaction_id."', user_id='".
        mysql_real_escape_string($_USER['uid'])."', pool_id='".mysql_real_escape_string($pool_id)."'"; 
$result = mysql_query($query,$db);
if(!$result) {
    //query failed
	//TODO do something here
    die("query failed, query: ".$query."\n error:".mysql_error());
}

//print the result
print_formatted_result($transaction_id,$format,$callback);

?>
