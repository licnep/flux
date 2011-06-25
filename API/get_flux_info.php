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

//FOR DEBUG: (enable error reporting)
ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT);

$flux_id = $_GET['flux_id'];
if (isset($_GET['format'])) $format = $_GET['format'];
else $format = "json"; //default format is json

print_flux_info($flux_id,$format);

function print_flux_info($flux_id,$format = "json") {
	require_once('execute_query.php');
	$db = db_connect("flux_changer");
	$query = "SELECT flux_from_id, flux_to_id, share FROM
		fluxes, routing
		WHERE fluxes.flux_id='".mysql_real_escape_string($flux_id).
		"' AND routing.flux_from_id = fluxes.flux_id";
	$result = mysql_query($query,$db);
	if(!$result) {//TODO something
		die("query failed, query: ".$query."\n error:".mysql_error());
	}
	while($row = mysql_fetch_array($result)) {
		echo $row['flux_from_id']." TO ".$row['flux_to_id']." SHARE: ";
		?> <form action="change_flux.php" method="GET">
		<input type="hidden" name="flux_from_id" value="<?=$row['flux_from_id']?>" />
		<input type="hidden" name="flux_to_id" value="<?=$row['flux_to_id']?>" />
		<input type="hidden" name="redirect" value="get_flux_info.php?flux_id=<?=$flux_id?>">		
		<input type="text" name="new_share" value="<?=$row['share']?>" /><input type="submit"/>
		</form>
		<?php
	}

}
?>
