<form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
Amount:<input type="text" name="amount" value="5.00" /> USD
<input type="hidden" name="cmd" value="_donations">
<input type="hidden" name="notify_url" value="http://178.254.1.64/flux/API/paypal/IPN.php" />
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="return" value="http://178.254.1.64/flux/website/account_home.php">
<input type="hidden" name="cancel_return" value="http://178.254.1.64/flux/website/account_home.php">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cbt" value="Return to FLUX.COM">
<input type="hidden" name="business" value="seller_1309852781_biz@yahoo.com">
<input type="hidden" name="lc" value="US">

<input type="hidden" name="item_name" value="FLUXcorporation">
<input type="hidden" name="item_number" value="3"><!--<< the flux id for now, maybe something more complex later -->
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
</form>

