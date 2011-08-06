<?php

#FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

/**
Executes a query on a database. The user and database to be used are identified
by $id.
If the query fails it does something, but what?
Possibly 
* - print out an error
* - throw an exception
* - send an email to the admins
*/
function db_connect($id) {

	$db_name = "testfluxAPI";
	switch($id) {
		case "flux_changer": $username="root"; $password="zxcvbnm"; break;
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
