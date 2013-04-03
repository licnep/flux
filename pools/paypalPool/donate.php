<h1>Donate with paypal</h1>
<?php
if (!isset($_GET['transaction_key'])) {
	die("this page should not be accessed directly, come back when you have a transaction key.");
}
?>
If you aren't already logged in to the paypal sandbox login to the sandbox before trying to donate.<br/>
Go to <a href="http://sandbox.paypal.com">http://sandbox.paypal.com</a>, and login with:<br/>
email: lsnpreziosi@yahoo.com<br/>
password: 56A2750B8B<br/>
Once you're logged in you can use sandbox accounts to test the transactions. The default one is:<br/>
email: lsnpre_1235310181_per@yahoo.com<br/>
password: 00000000  (8 zeros)<br/>
<br/>
<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
<!--Amount:<input type="text" name="amount" value="5.00" /> USD-->
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="notify_url" value="http://flux.lolwut.net/flux/pools/paypalPool/paypal/IPN.php" />
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="return" value="http://flux.lolwut.net/flux/website/account_home.php">
<input type="hidden" name="cancel_return" value="http://flux.lolwut.net/flux/website/account_home.php">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cbt" value="Return to FLUX.COM">
<input type="hidden" name="business" value="seller_1309852781_biz@yahoo.com">
<input type="hidden" name="lc" value="US">

<input type="hidden" name="item_name" value="FLUXcorporation">
<input type="hidden" name="item_number" value="<?=$_GET['transaction_key']?>">
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
</form>
<br/>