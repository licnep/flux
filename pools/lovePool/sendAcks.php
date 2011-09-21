<h1>Send ACKS</h1>
Normally, the pool server will continuosly check which transactions haven't yet been ACKd by the flux backend and keep sending their data, but for now (we're just testing) we do it by hand.<br/>
Every time you load this page, all the ack requests for unACKd transactions are sent.<br/>
<h2>UnACKED transactions:</h2>
<small>(if you reload the page it will send ack requests for these transactions)</small>
<?php
require_once("db_connect.php");
$db = db_connect();
$query = "SELECT * FROM transactions WHERE ack=0";
$result = mysql_query($query,$db);
if(!$result) {
    die("Transaction FAILED, query: ".$query."\n error:".mysql_error());
}
?>
<ul><?php
while($row = mysql_fetch_array($result)) {
	/*for each transaction yet to confirm, we send the confirmation request:*/
	send_ack_request($row['transaction_id'],$row['amount']);

	?><li>ID: <?=$row['transaction_id']?>, amount: <?=$row['amount']?></li><?php
}
?>
</ul>

<?php

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
	require_once("get_webpage.php");
        include("LocalSettings.php");
	$url = $C_API_base_url."/pool/ack_transaction.php?".
			"transaction_id=".$transaction_id.
			"&&amount=".$amount.
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
		if ($result==1) echo "SUCCESS!!";
		else echo $response;
	}
}

?>
