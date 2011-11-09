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

include('API_common.php');

//get the user_id (you can request this info for any user)
$user_id = $_GET['user_id'];

$result = get_fluxes_owned_by($user_id);
$rows = array();
while($r = mysql_fetch_assoc($result)) {
	array_push($rows,$r);
}
print_formatted_result($rows,$format,$callback);

function get_fluxes_owned_by($user_id) {
	require_once('execute_query.php');
	$db = db_connect("flux_changer");
	$query = "SELECT flux_id, name, description,userflux FROM
		fluxes
		WHERE owner='".mysql_real_escape_string($user_id)."' AND userflux!=1;";
	$result = mysql_query($query,$db);
	if(!$result) {//TODO something
		die("query failed, query: ".$query."\n error:".mysql_error());
	}
	return $result;

}
?>
