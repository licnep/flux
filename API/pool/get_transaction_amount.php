<?php
include(dirname(__FILE__).'/../API_common.php');

/*
 * This can be used by a pool to find out the amount of money that a user has the right to withdraw.
 * This given the right transaction key of course.
 * 
 * Input:
 * transaction_id
 */
if (!isset($_GET['transaction_id'])) die ("'transaction_id' is not set.");
$transaction_id = $_GET['transaction_id'];
$db = db_connect();
$result = mysql_query("SELECT amount,total FROM transactions AS t, pools AS p WHERE transaction_id='$transaction_id' and t.pool_id = p.pool_id");
if (!$result) {die("error:".  mysql_error());}
$row = mysql_fetch_array($result);
$result = array(
    "total"=>$row['total'],
    "amount"=>$row['amount']
);
print_formatted_result($result, $format,$callback);

?>
