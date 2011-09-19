<?php

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

function db_connect($id = "") {

    include("LocalSettings.php");

	$db_name = "lovePool";
	switch($id) {
		default : $username=$C_username; $password=$C_password; break;
	}
	//making a persistent connection (it automatically uses an open one if it exists)
	$db = mysql_pconnect('localhost',$username,$password);

	if(!isset($db)) {
		//TODO ERROR!!! DO SOMETHING USEFUL
		die("db opening error");
	}
	//selecting the database:
	$db_sel_result = mysql_select_db($db_name);
	if ($db_sel_result==FALSE) {
		//TODO do something here
		die("db selecting error");
	}
	return $db;
}

?>
