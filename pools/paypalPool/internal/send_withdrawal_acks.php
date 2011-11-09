<?php
require_once(dirname(__FILE__).'/common/error_handling.php');

/**
This function gets the most recent unacked withdrawal transaction and tries to send an ACK to the flux api.
This function should be called frequently from a cron job or something.
IMPORTANT: withdrawal acks can be either positive or negative, in case the withdrawal failed.
*/
function send_withdrawal_acks() {
    require_once("db_connect.php");
    $db = db_connect();
    $query = "SELECT * FROM transactions WHERE ack=0 LIMIT 1";
    $result = mysql_query($query,$db);
    if(!$result) {
        E_notify("Transaction FAILED, query: ".$query."\n error:".mysql_error());
        die("Transaction FAILED, query: ".$query."\n error:".mysql_error());
    }
    if (mysql_num_rows($result)==0) {return;}
    $row = mysql_fetch_array($result);
    send_ack_request($row['transaction_id'],$row['amount']);
}

function send_ack_request($transaction_id,$amount) {
	/*First we need to sign the transaction data with our private key*/

	/**
	phase 1: generating the signature
	**/
$private_key = "-----BEGIN RSA PRIVATE KEY-----
MIIBOgIBAAJBANDiE2+Xi/WnO+s120NiiJhNyIButVu6zxqlVzz0wy2j4kQVUC4Z
RZD80IY+4wIiX2YxKBZKGnd2TtPkcJ/ljkUCAwEAAQJAL151ZeMKHEU2c1qdRKS9
sTxCcc2pVwoAGVzRccNX16tfmCf8FjxuM3WmLdsPxYoHrwb1LFNxiNk1MXrxjH3R
6QIhAPB7edmcjH4bhMaJBztcbNE1VRCEi/bisAwiPPMq9/2nAiEA3lyc5+f6DEIJ
h1y6BWkdVULDSM+jpi1XiV/DevxuijMCIQCAEPGqHsF+4v7Jj+3HAgh9PU6otj2n
Y79nJtCYmvhoHwIgNDePaS4inApN7omp7WdXyhPZhBmulnGDYvEoGJN66d0CIHra
I2SvDkQ5CmrzkW5qPaE2oO7BSqAhRZxiYpZFb5CI
-----END RSA PRIVATE KEY-----";
	$data = $transaction_id.$amount;
	$signature="";
	openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA1);


	/**
	END phase 1: signature generated and stored in $signature
	**/

	/**
	phase 2: send the ACK request
	**/
	require_once(dirname(__FILE__).'/common/get_webpage.php');
    include("LocalSettings.php");
	$url = $C_API_base_url."/pool/ack_transaction.php?".
			"transaction_id=".$transaction_id.
			"&amount=".$amount.
            "&amount_readable=".$amount.urlencode(" $").
			"&signature=".urlencode(base64_encode($signature));
	$response=get_webpage($url);
	if ($response=="SUCCESS") {
		$db = db_connect();
		$query="UPDATE transactions SET ack=1 WHERE transaction_id='".
				mysql_real_escape_string($transaction_id)."'";
		$result = mysql_query($query,$db);
		if(!$result) {
			die("Transaction FAILED, query: ".$query."\n error:".mysql_error());
		}
		if ($result==1) {
            return true;
		}
		else {
            E_notify('Error sending ack. Response:'.$response);
            return false;     	
		}
	} else {
            E_notify('Error sending ack. Response:'.$response);
            return false;
        }
}

?>
