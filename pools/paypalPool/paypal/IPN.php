<?php
require_once(dirname(__FILE__).'/../internal/common/error_handling.php');

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

$raw_post_data = file_get_contents('php://input');

$req .='&'.$raw_post_data;

// post back to PayPal system to validate
	
$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
 
	// If testing on Sandbox use: 
$header .= "Host: www.sandbox.paypal.com:443\r\n";
//$header .= "Host: www.paypal.com:443\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";

	// If testing on Sandbox use:
$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);

// assign posted variables to local variables
$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

if (!$fp) {
// TODO log the error
} else {
fputs ($fp, $header . $req);
while (!feof($fp)) {
$res = fgets ($fp, 1024);
if (strcmp ($res, "VERIFIED") == 0) {
    // TODO check the payment_status is Completed
    // TODO check that txn_id has not been previously processed
    // TODO check that receiver_email is your Primary PayPal email
    // TODO process payment
    
    //let's see if it's a successfull donation transaction:
    if (is_successful_donation($_POST)) {
        E_log("it's a successful donation");
        
        require_once('../internal/store_transaction.php');
        $result = store_transaction($_POST['item_number'],$_POST['mc_gross']-$_POST['mc_fee']);
        // TODO check that we stored the transaction successfully
    }
    else if (is_successful_withdrawal($_POST,$raw_post_data)) {
        E_log('its a successful withdrawal');
        E_log('RAW='.$raw_post_data);
        require_once('../internal/store_withdrawal.php');
        $key = $_POST['tracking_id'];
        $postArray = getIPNResponseObject($raw_post_data);
        $amount = $postArray['transaction'][0]['amount'];
        $amount = substr($amount,4); //the first 3 characters are the currency
        store_withdrawal($key,$amount,1);
    }
    
    E_log('VERIFIED IPN');
}
else if (strcmp ($res, "INVALID") == 0) {
    // log for manual investigation
    E_log('INVALID IPN');
}
}
fclose ($fp);
}

function is_successful_donation(&$post) {
    if(!isset($post['business'])) return false;
    if($_POST['business']!='seller_1309852781_biz@yahoo.com') {
        E_notify('business='.$post['business']);
        return false;
    }
    if(!isset($post['payment_status']) || $post['payment_status']!='Completed') return false;
    
    return true;
}

function is_successful_withdrawal(&$post,&$rawpost) {
    if (!isset($post['sender_email'])) return false;
    if ($post['sender_email']!='seller_1309852781_biz@yahoo.com') {
        E_notify('sender email='.$post['sender_email']);
        return false;
    }
    if(!isset($post['action_type']) || $post['action_type']!='PAY') {
        E_notify('action type='.$post['action_type']);
        return false;
    }
    if(!isset($post['status']) || $post['status']!='COMPLETED') {
        return false;
    }
    
    //look for a substring saying the transaction status is completed inside the raw post.
    //we do this because the 'transaction' post variable is an array, and i don't know
    //how to handle it from php
    if (!strrpos($rawpost,'transaction%5B0%5D.status=Completed') ) { 
        //sometimes the status is just 'Pending'. eg. when the user has an account in a different currency 
        //so he has to manually accept the payment
        return false;
    }
    return true;
}

/*
This function is needed to parse the post data from adaptive payment ipn, because they contain arrays
*/
function getIPNResponseObject( $raw_post_data = null ){
    if( empty($raw_post_data) ){
        $raw_post_data = file_get_contents('php://input');
    }
    $string = preg_replace("/(\.(\w*))=/i", "%5B$2%5D=", $raw_post_data);
    $a = array();
    parse_str($string, &$a);
    return $a;
}

?>

