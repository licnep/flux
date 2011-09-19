<?php
$_USER = array();
$hash = $_GET['key'];
if (!check_API_key($hash)) {
	die('WRONG API KEY');
}

function check_API_key($hash) {
	global $_USER;

	require_once(dirname(__FILE__) . '/execute_query.php');
	$db = db_connect("flux_changer");

	$query = "SELECT * FROM users WHERE hash='".mysql_real_escape_string($hash)."'"; 
	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	if (mysql_num_rows($result)==1) {
		$row = mysql_fetch_array($result);
		$_USER['uid'] = $row['user_id'];
		return true;
	}
	else {
		return false;
	};
}
?>
