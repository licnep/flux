<html>
<body>
<h1>Congratulations!</h1>
Your account is registered,but you need to confirm your cellphone number.
<br/>
<br/>
Insert the activation code you received here:
<form action="http://localhost/API/confirm_account.php" method="GET">
<p>Code: <input type="text" name="confirmation_code" value="123"></p>
<input type="hidden" name="user_id" value="2"/>
<input type="hidden" name="redirect" value="http://localhost/website/account_home.php"/>
<input type="submit" />
</form>
</body>
</html>
