<div id="topBar">
<?php
if (!isset($_SESSION['logged'])||!($_SESSION['logged'])) echo ('<a href="login.php?goto='.urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'">'.'Log in</a> or <a href="register.php">Register</a>');
else echo (sprintf('Goodmorning <a href="me.php">%s</a>,',$_SESSION['username']) .' <a href="scripts/logout.php?goto='.urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'">'.'logout</a>');
?>
</div>
<!-- if he's a temporary user we show the warning bar-->
<?php
if ($_SESSION['temp']==1) {
?>
<div class="warning">
    Welcome <?=$_SESSION['username']?>, <a href="register.php">click here</a> to change your nick, or <a href="login.php">login</a> if you already have an account.
</div>
<?php } ?>

