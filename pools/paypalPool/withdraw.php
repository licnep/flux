<?php

if (!isset($_GET['transaction_key'])) die("transaction_key is not set.");
else $key = $_GET['transaction_key'];

if(!isset($_POST['receiver'])) {
    ?>
    <form method="post" >
    <p>Insert your (sandbox) paypal address: <input type="text" size="40" name="receiver" value="lsnpre_1235310181_per@yahoo.com" /></p>
    <input type="submit"/>
    </form>
    <?php
    return;
}
else $receiver = $_POST['receiver'];

/*
 * Now the pool doesn't know how much money the user has a right to withdraw. 
 * Therefore the pool asks to the flux API (by sending the transaction key).
 */

require_once(dirname(__FILE__)."/internal/common/get_webpage.php");
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../../API/pool/get_transaction_amount.php?transaction_id=$key";
$data = json_decode(get_webpage($url));
if(!isset($data)) die("error getting the json transaction amount from the API");

//the next line is very simplified, we should actually make a conversion
$amount = $data->{'amount'};

require_once(dirname(__FILE__).'/paypal/send_money.php');
send_money($key,$amount,$receiver);

?>

