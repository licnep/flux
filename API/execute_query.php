<?php
/*
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *                    Version 2, December 2004
 * 
 * Copyright (C) 2011 Alessandro Preziosi <lsnpreziosi+f@gmail.com>
 * Everyone is permitted to copy and distribute verbatim or modified
 * copies of this document, and changing it is allowed as long as the 
 * name is changed.
 *
 *            DO WHAT THE FUCK YOU WANT TO PUBLIC LICENSE
 *   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION
 * 
 *  0. You just DO WHAT THE FUCK YOU WANT TO.
 *
 * ===================================================================
 *  "I can't claim ownership over this document.
 *   I did not invent the language I'm speaking, the lamp lighting my 
 *   desk, or the computer I'm typing this on.
 *   We are dwarfs sitting on the shoulders of giants.
 *   It's our turn now. BE THE GIANT."
 */

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
		case "flux_changer": $username="insertusername"; $password="insertpassword"; break;
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
