<?php
//FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

function add_money_in_database($flux_id,$amount) {
	require_once('../execute_query.php');
	$db = db_connect("flux_changer");
	$query = "UPDATE fluxes SET money = money +'".mysql_real_escape_string($amount).
		"' WHERE flux_id=".mysql_real_escape_string($flux_id);
	$result = mysql_query($query,$db);
	if(!$result) {//TODO something
		mail("lsnpreziosi@gmail.com","FUCK!","fuck, error in inserting money in the database, too much money!! query=".$query."\n<br>Error:".mysql_error());
		die("D:");
	}
}
?>
