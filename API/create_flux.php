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

/**
 * Params:
 * name: the name of the flux you want to create
 * opt: [OPTIONAL] any additional data related to the flux
 */

include('API_common.php');

$user_id = $_GET['user_id'];
$name = isset($_GET['name'])? $_GET['name'] : '';
$opt = isset($_GET['opt'])? $_GET['opt'] : ''; 
$result = create_flux($user_id,$name,$opt);

require_once('print_formatted_result.php');
print_formatted_result($result,$format,$callback);

function create_flux($user_id,$name,$opt) {
	//TODO login check

	require_once('execute_query.php');
	$db = db_connect("flux_changer");

	$user_id = mysql_real_escape_string($user_id);
	$name = mysql_real_escape_string($name);
	$opt = mysql_real_escape_string($opt);

	$query = "INSERT INTO fluxes SET name='$name', owner='$user_id', opt='$opt'";
	$result = mysql_query($query,$db);
        if(!$result) {
            //query failed
            //TODO do something here
            die("query failed, query: ".$query."\n error:".mysql_error());
        }
        
        
	return true;
}
?>
