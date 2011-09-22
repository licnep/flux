<?php
include(dirname(__FILE__).'/../API_common.php');

/*
 * This freezes the money in the user account and generates a withdrawal transaction key.
 * This key has to be passed to the pool to withdraw.
 * The money will stay freezed forever, unless the pool says the transaction failed.
 */

//check the api key, if it's wrong log out
//only logged_in users can get a withdrawal key
include(dirname(__FILE__).'/../check_API_key.php');

$db = db_connect();

/*
 * we need to make a transaction of 3 queries, that must be both successful.
 * 1) we 'empty' the user account
 * 2) we put that amount of money in the 'amount' field of the transaction.
 */
$uid =  mysql_real_escape_string($_USER['uid']);

/*no other script must be able to change the fluxes table during this operation.*/
mysql_query("LOCK TABLES fluxes WRITE"); 

/*
 * query 1. We find out the amount of money in the user account and put it in $amount
 */
$r1 = mysql_query("SELECT money FROM fluxes WHERE owner='$uid' AND userflux=1");
if (!$r1) {echo mysql_error();}
else {
    $row = mysql_fetch_array($r1);
    $amount = $row['money'];
}
/*
 * query 2. we remove all the money from the user account.
 */
mysql_query("START TRANSACTION");
$r2 = mysql_query("UPDATE fluxes SET money=0 WHERE owner='$uid' AND userflux=1");
/*
 * query 3. We actually create that withdraw transaction, with amount=$amount
 */
//generate the unique transaction id:
$transaction_id = uniqid(); 
$r3 = mysql_query("INSERT INTO transactions SET transaction_id='$transaction_id', user_id='$uid',pool_id=1,flux_to_id=0,amount='$amount',type=1");
if (!$r3) {
    echo mysql_error();
}
if ($r2 and $r3) {$result = mysql_query("COMMIT");} else {$result = mysql_query("ROLLBACK");}

mysql_query("UNLOCK TABLES");

//all is good.

?>
