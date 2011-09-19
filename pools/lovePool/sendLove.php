<h1>Send love</h1>
<?php
if (!isset($_GET['transaction_key'])) {
	die("this page should not be accessed directly, come back when you have a transaction key.");
}
?>
Are you sure you want to do this?<br/>
<br/>
<form method="post" action="storeTransaction.php">
<input type="hidden" name="transaction_key" value="<?=$_GET['transaction_key'];?>"/>

<p>Insert the amount of love you want to store: <input type="text" name="amount" value="5.00"/></p>
<p>
Now think intensely about someone you love and hug your monitor ^o^ <3 <3. </p>
<p>When you're done click here:<input type="submit"></p>
</form>
