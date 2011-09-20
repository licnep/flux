<?php
include(dirname(__FILE__).'/../API_common.php');

/******************
GET: 
	transaction_id
	amount
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
We look for the transaction in the database, and also retrieve the public key associated with this particular pool
*/
$transaction_id = $_GET['transaction_id'];
$db = db_connect();
$query = "SELECT public_key, status, ack_url
		  FROM transactions AS t, pools AS p
		  WHERE t.transaction_id='".mysql_real_escape_string($transaction_id)."' AND t.pool_id=p.pool_id";
$result = mysql_query($query,$db);
if (!$result) {die("fatal error");}
if (mysql_num_rows($result)!=1) {die("No such transaction, QUERY:".$query." ERROR:".mysql_error());}
$row = mysql_fetch_array($result);
$ack_url = $row['ack_url'];

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
$query = "UPDATE transactions SET status=1 WHERE transaction_id='".mysql_real_escape_string($transaction_id)."'";
$result = mysql_query($query,$db);
if (!$result) {die ('ERROR during update, Query:'.$query.' Error:'.mysql_error());} 


/*
EVERYTHING OK
we can notify the pool with the ACK message now.
*/
echo "SUCCESS";


?>
