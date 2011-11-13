<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<link rel="stylesheet/less" type="text/css" href="css/bootstrap/lib/bootstrap.less">
<script src="css/bootstrap/less.js" type="text/javascript"></script>
<div class="container">
    <h2>Withdraw:</2>
<?php

if (!isset($_GET['transaction_key'])) die("transaction_key is not set.");
else $key = $_GET['transaction_key'];

if(!isset($_POST['receiver'])) {
    ?>
    <form method="post" >
    <p>Insert your paypal email: <input type="text" size="40" class="xxlarge" name="receiver" value="lsnpre_1235310181_per@yahoo.com" /></p>
    <input type="submit" class="btn primary"/>
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
include('internal/LocalSettings.php');
$url = $C_API_base_url."/pool/get_transaction_amount.php?transaction_id=$key";
$data = json_decode(get_webpage($url));
if(!isset($data)) die("error getting the json transaction amount from the API");

//the next line is very simplified, we should actually make a conversion
$amount = $data->{'amount'};

require_once(dirname(__FILE__).'/paypal/send_money.php');
send_money($key,$amount,$receiver);

?>
</div>
