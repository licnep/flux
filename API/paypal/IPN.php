
<?php
// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

foreach ($_POST as $key => $value) {
$value = urlencode(stripslashes($value));
$req .= "&$key=$value";
}

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
// check the payment_status is Completed
// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
// check that payment_amount/payment_currency are correct
// process payment

$mail_From = "From: me@mybiz.com";
$mail_To = "lsnpreziosi@gmail.com";
$mail_Subject = "VERIFIED IPN";
$mail_Body = $req;

mail($mail_To, $mail_Subject, $mail_Body, $mail_From);


}
else if (strcmp ($res, "INVALID") == 0) {
// log for manual investigation

$mail_From = "From: me@mybiz.com";
$mail_To = "lsnpreziosi@gmail.com";
$mail_Subject = "INVALID IPN";
$mail_Body = $req;

mail($mail_To, $mail_Subject, $mail_Body, $mail_From);

}
}
fclose ($fp);
}
?>

