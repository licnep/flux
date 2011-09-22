<?php
require_once(dirname(__FILE__)."/API_common.php");
/*
 * This searches for fluxes matching string and returns a list of fluxes.
 * 
 * Input:
 * - string 
 */

$result = search_flux($_GET['string']);
print_formatted_result($result, $format,$callback);

function search_flux($string) {
    $db = db_connect();
    $string = mysql_real_escape_string($string);
    $query = "SELECT * FROM fluxes WHERE name LIKE '$string%'";
    $result = mysql_query($query);
    if (!$result) {die("XP query:".$query." error:".mysql_error());}
    $rows = array();
    while($r = mysql_fetch_assoc($result)) {
            array_push($rows,$r);
    }
    return $rows;
}

?>
