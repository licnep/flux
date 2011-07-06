<html>
<body>
<h1>[This is your persoanl page. Welcome]</h1>
<h2>My FLUX:</h2>
<?php include("include/paypal_donate_button.php"); ?>
<div>
<iframe src="../API/get_flux_info.php?flux_id=1"></iframe>
</div>
<h2>Account Balance:</h2>
You have 0.00 $ <input type="submit" value="Withdraw"/>
<h2>My projects:</h2>
<iframe src="../API/get_fluxes_owned_by.php?user_id=1"></iframe>
<br/>
<a href="new_project.php">New project</a>
</body>
</html>
