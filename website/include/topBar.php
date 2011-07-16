<div id="topBar">
<?php
if (!isset($_SESSION['logged'])||!($_SESSION['logged'])) echo ('<a href="login.php?goto='.urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'">'.'Log in</a> or <a href="register.php">Register</a>');
else echo (sprintf('Goodmorning <a href="me.php">%s</a>,',$_SESSION['username']) .' <a href="scripts/logout.php?goto='.urlencode('http://'.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]).'">'.'logout</a>');
?>
</div>
