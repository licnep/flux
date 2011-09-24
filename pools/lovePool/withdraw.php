<?php

if (!isset($_GET['transaction_key'])) die("transaction_key is not set.");
else $key = $_GET['transaction_key'];

if (!isset($_GET['successful'])) {
?>
<form method="get" action="">
    <h2>Do you feel more loved?</h2>
    <p>Enter 1 if the withdrawal of love was successful or 0 if it wasn't:<input type="text" name="successful" value="1"/></p>
    <input type="hidden" name="transaction_key" value="<?=$_GET['transaction_key']?>"/>
    <input type="submit" value="submit"/>
</form>
<?php exit();} 
//if we're here then 'success'successful' is set.

/*
 * Now the pool doesn't know how much money the user has a right to withdraw. 
 * Therefore the pool asks to the flux API (by sending the transaction key).
 */

require_once(dirname(__FILE__)."/get_webpage.php");
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../../API/pool/get_transaction_amount.php?transaction_id=$key";
$data = json_decode(get_webpage($url));
if(!isset($data)) die("error getting the json transaction amount from the API");

//the next line is very simplified
$amount = $data->{'amount'};

if ($_GET['successful']=="1") {
    send_ack_request($key,$amount,1);
} else {
    send_ack_request($key,$amount,0);
}

/**
 *
 * @param string $key
 * @param bool $success
 */
function send_ack_request($key,$amount,$success) {
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
    $data = $key.$amount;
    $signature="";
    openssl_sign($data, $signature, $private_key, OPENSSL_ALGO_SHA1);


    /**
    END phase 1: signature generated and stored in $signature
    **/

    /**
    phase 2: send the ACK request
    **/
    include("LocalSettings.php");
    $url = $C_API_base_url."/pool/ack_withdrawal.php?".
                    "status=".($success?"SUCCESS":"FAIL").
                    "&transaction_id=".$key.
                    "&amount=".$amount.
                    "&signature=".urlencode(base64_encode($signature));
    $response=get_webpage($url);
    if ($response=="SUCCESS") {
            require_once("db_connect.php");
            $db = db_connect();
            $query="UPDATE transactions SET ack=1 WHERE transaction_id='".
                            mysql_real_escape_string($key)."'";
            $result = mysql_query($query,$db);
            if(!$result) {
                    die("Transaction FAILED, query: ".$query."\n error:".mysql_error());
            }
            if ($result==1) {
                if ($success) {
                    echo "SUCCESS!! you have withdrawn $amount.";
                } else {echo "ok, the love withdrawal failed. But we put the amount back in your flux account.";}
            }
            else echo $response;
    } else {
        echo $response;
    }
}


?>

