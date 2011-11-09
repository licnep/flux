<?php
include(dirname(__FILE__).'/../API_common.php');
/*
 * Input:
 * -status (either "SUCCESS" or "FAIL")
 * -transactioni_id
 * -amount
 * -amount_readable
 * -signature (the rsa signature of the string $transaction_id.$amount, encoded in base64)
 * 
 */
$db = db_connect();

/*
 * The response must specify wheter the withdrawal is successful or failed.
 */
if (!isset($_GET['status'])) die("'status' must be set to either 'SUCCESS' or 'FAIL'");
$status = $_GET['status'];
if (!isset($_GET['transaction_id'])) die("'transaction_id' must be set.");
$transaction_id = mysql_real_escape_string($_GET['transaction_id']);
if (!isset($_GET['amount'])) die("'amount' must be set.");
$amount = mysql_real_escape_string($_GET['amount']);
if (!isset($_GET['amount_readable'])) die("'amount_readable' must be set.");
$amount_readable = mysql_real_escape_string($_GET['amount_readable']);

/*
 * 1) Retrieve the transaction and the pool data from the database.
 * We do this to check that the transaction exists and to later check the pool signature
 */

$query = "SELECT status, public_key, ack_url
            FROM transactions AS t, pools AS p
		  WHERE t.transaction_id='$transaction_id' AND t.pool_id=p.pool_id AND p.pool_id=1";
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
 * If we're here everything looks legit. Now what we do depends on whether the withdrawal was successful or failed.
 */
if ($status=="SUCCESS") {
    /*
     * The withrawal was successful. We confirm the transaction and (IMPORTANT) remove that amount of money from the pool's total 
    */
    mysql_query("START TRANSACTION");
    $r1 = mysql_query("UPDATE pools SET total=total-$amount WHERE pool_id=1");
    if (!$r1) echo "error:".mysql_error();
    $r2 = mysql_query("UPDATE transactions SET status=1,amount='$amount',amount_readable='$amount_readable' WHERE transaction_id='$transaction_id'");
    if (!$r2) echo "error:".mysql_error();
    if ($r1 and $r2) {$result = mysql_query("COMMIT");} else {$result = mysql_query("ROLLBACK");}
    if (!$result) {die(mysql_error()) ;}
    echo "SUCCESS";
    return;
} else if ($status=="FAIL") {
    /*
     * The pool is telling us that for some reason the withdrawal failed. So we should unfreeze the mouney and put it back into the user account.
     * And also render the withdrawal key unusable again.
    */
    mysql_query("START TRANSACTION");
    /*nullify the transaction key, it can't be used anymore (do this by setting status to 2)*/
    $r1 = mysql_query("UPDATE transactions SET status=2 WHERE transaction_id='$transaction_id'");
    if (!$r1) echo "error:".mysql_error();
    /*put the money back into the user account*/
    $query = "UPDATE fluxes as f, transactions as t 
                    SET f.money=f.money+t.amount 
                    WHERE t.transaction_id='$transaction_id' AND t.user_id=f.owner and f.userflux=1";
    $r2 = mysql_query($query);
    if (!$r2) echo "error:".mysql_error()." query:".$query;
    if ($r1 and $r2) {$result = mysql_query("COMMIT");} else {$result = mysql_query("ROLLBACK");}
    if (!$result) {die(mysql_error()) ;}
    echo "SUCCESS";
    return;
} else {
    die("status must be set to either SUCCESS or FAIL, currently set to:".$status);
}


?>
