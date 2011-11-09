<?php
include(dirname(__FILE__).'/../API_common.php');

/******************
GET: 
	transaction_id
	amount
    amount_readable
	signature (a base64 encoded binary RSA signature of the string $transaction_id.$amount)
******************/

/*************************************
This API call confirms a transaction in a pool.
Requirements:
- The transaction must already exist (unconfirmed) in the backend database
- The transaction is signed correctly by the pool. (the backend stores the public key for every pool)
**************************************/

/*
CHECK n.1
We look for the transaction in the database, and also retrieve the public key associated with this particular pool.
 * We do this to verify that the transaction key is correct (exists, and is associated with the pool), and to later check
 * the pool's signature.
*/
$transaction_id = $_GET['transaction_id'];
$db = db_connect();
$query = "SELECT status, flux_to_id, public_key , ack_url
		  FROM transactions AS t, pools AS p
		  WHERE t.transaction_id='".mysql_real_escape_string($transaction_id)."' AND t.pool_id=p.pool_id AND p.pool_id=1";
$result = mysql_query($query,$db);
if (!$result) {die("fatal error");}
if (mysql_num_rows($result)!=1) {die("No such transaction, QUERY:".$query." ERROR:".mysql_error());}
$row = mysql_fetch_array($result);
$ack_url = $row['ack_url'];
$flux_to_id = $row['flux_to_id'];

if($row['status']!=0) {
	/*Apparently we've already confirmed the transaction, just send the ACK message back*/
	echo "SUCCESS";
	return;
}

/*
CHECK n.1 COMPLETE!
If we're here the first check went OK. Now we gotta make sure that this call comes from a verified pool and not some shitty skiddy.
We do that by verifying the RSA signature.
*/

$public_key = $row['public_key'];
$data = $_GET['transaction_id'].$_GET['amount'];
$signature=base64_decode($_GET['signature']);

// Check signature
$ok = openssl_verify($data, $signature, $public_key, OPENSSL_ALGO_SHA1);

if ($ok!=1) {die("Signature Error, you fucking trying to fool me motherfucker?!!!");}

/*
CHECK N.2 COMPLETE!
If we're here everything is ok, we just gotta store the transaction in our db and send the confirmation to the pool
*/

/*
 * We gotta make 2 queries in a transaction.
 * 1) First we set the transaction status to 1 (acknowledged).
 * 2) Then we put the amount of money in the flux, to start routing.
 */
$amount = mysql_real_escape_string($_GET['amount']);

mysql_query("START TRANSACTION");
$r1 = mysql_query("UPDATE transactions SET status=1,amount='$amount',amount_readable='"
    .mysql_real_escape_string($_GET['amount_readable'])."' WHERE transaction_id='".mysql_real_escape_string($transaction_id)."'");
if (!$r1) {echo mysql_error();}
$r2 = mysql_query("UPDATE fluxes SET money=money+'$amount' WHERE flux_id='$flux_to_id'");
if (!$r2) {echo mysql_error();}
$r3 = mysql_query ("UPDATE pools SET total=total+$amount WHERE pool_id=1");
if (!$r3) {echo mysql_error();}

if (($r1 and $r2) and $r3) {$result = mysql_query("COMMIT");} else {$result = mysql_query("ROLLBACK");}

if (!$result) {die ('ERROR during transaction, Error:'.mysql_error());} 

/*
 * Let's move the money a little by 'pushing' it through the receiver flux, so it's immediately
 * divided among the receivers.
 */
$result = mysql_query("CALL move_money($flux_to_id)");

/*
EVERYTHING OK
we can notify the pool with the ACK message now.
*/
echo "SUCCESS";


?>
