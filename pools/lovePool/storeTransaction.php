<?php

require_once("db_connect.php");

$db = db_connect();

$query = "INSERT INTO transactions SET transaction_id='".mysql_real_escape_string($_POST['transaction_key']).
		 "', amount=".mysql_real_escape_string($_POST['amount']);
$result = mysql_query($query,$db);
if(!$result) {
    die("Transaction FAILED, query: ".$query."\n error:".mysql_error());
}
//if we're here we stored the transaction correctly, now we'll repeatedly notify the flux backend until we get a response it has aknowledged the transaction

header("location: sendAcks.php");
?>
