<html>
<body>
<h1>[Name of this flux]</h1>
<div style="border: 1px solid black">
<h2>Info that should be shown somewhere:</h2>
* description of the flux, of what donations will be used for

<h2>Routing:</h2>
<iframe src="../API/get_flux_info.php?flux_id=<?=$_GET['id']?>"></iframe>
<h2>Add this flux to one of your fluxes:</h2>
<iframe src="../API/list_my_fluxes.php"></iframe>
<form method="get" action="../API/change_flux.php">
<input type="hidden" name="redirect" value="account_home.php" />
<input type="hidden" name="flux_from_id" value="1" />
<input type="hidden" name="flux_to_id" value="2" />
Share: <input type="text" name="new_share" value="1"/>
<input type="submit" value="Add to flux n. 1" />
</form>
</div>
</body>
</html>
