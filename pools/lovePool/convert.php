<?php
/*
 * This script, gets a percentage and returns the absolute value in 'money',
 * according to the total amount of money currently present in the pool.
 * 
 * Input:
 * - amount
 * - total
 * 
 * (the percentage is amount/total)
 * 
 * Output:
 * a string representing the absolute amount.
 */

/*
 * 1) let's find out how much money we have in total.
 * we find out the sum of all successful deposit transaction,
 * and subtract the sum of all successful withdraw transactions
 */
require_once(dirname(__FILE__)."/db_connect.php");
$db = db_connect();
$query = "SELECT sum(amount) AS total FROM transactions WHERE type=0 AND ack=1";
$result = mysql_query($query);
if (!$result) {die("query: ".$query." -error:".mysql_error());}
$row = mysql_fetch_array($result);
$totalDeposited = $row['total'];

$query = "SELECT sum(amount) AS total FROM transactions WHERE type=1 AND ack=1";
$result = mysql_query($query);
if (!$result) {die("query: ".$query." -error:".mysql_error());}
$row = mysql_fetch_array($result);
$totalWithdrawn = $row['total'];
$poolTotal=$totalDeposited-$totalWithdrawn;

//if we're here we have the total

/*
 * 2) let's calculate the fraction
 */
if (!isset($_GET['amount'])) die("missing variable 'amount'");
    else $amount = $_GET['amount'];
if (!isset($_GET['total'])) die("missing variable 'total'");
    else $total = $_GET['total'];
/*the amount can never be greater than the total ofcourse!!*/
if ($amount>$total) die("ERROR amount greater than total.");

if ($_GET['total']==0) $amount=0; //avoid division by zero when the total is 0
else $amount = ($poolTotal*$_GET['amount'])/$_GET['total'];
echo number_format($amount, 2)." $";

?>
