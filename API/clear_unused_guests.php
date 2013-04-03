<?php
include('API_common.php');
require_once('execute_query.php');
$db = db_connect("flux_changer");

$query = "DELETE FROM users WHERE temp=1";
$result = mysql_query($query,$db);
if(!$result) {
    //query failed
    //TODO do something here
    die("query failed, query: ".$query."\n error:".mysql_error());
}

$query = "DELETE FROM fluxes WHERE owner NOT IN (select user_id from users)";
$result = mysql_query($query,$db);
if(!$result) {
    //query failed
    //TODO do something here
    die("query failed, query: ".$query."\n error:".mysql_error());
}
echo 'OK';
        
?>