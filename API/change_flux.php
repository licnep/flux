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

ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);


//we get the specifics for the transaction from the GET values passed
$flux_from_id = $_GET['flux_from_id'];
$flux_to_id = $_GET['flux_to_id'];
$new_share = $_GET['new_share'];

change_flux($flux_from_id,$flux_to_id,$new_share);

/**
 *  This function sets the amount of a specific receiver in a specific 
 *  flux.
 *
 *  EXTRA INFO:
 *  This function can be used only if you're logged in.
 *  
 */
function change_flux($flux_from_id,$flux_to_id,$new_share) {

	//TODO PSEUDOCODE:
	$loggedin = true; if (!$loggedin) return;
	//the user is logged in, can go on
	
	//TODO PSEUDOCODE (not implemented):
	require_once('execute_query.php');
	$db = db_connect("flux_changer");
	//TODO could try doing an overflow test with ridiculously high amounts
	$query = "UPDATE routing SET share=".mysql_real_escape_string($new_share).
			 " WHERE flux_from_id=".mysql_real_escape_string($flux_from_id).
			 " AND flux_to_id=".mysql_real_escape_string($flux_to_id);
	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
	echo "SUCCESS";
}

?>
