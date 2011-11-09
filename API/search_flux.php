<?php
require_once(dirname(__FILE__)."/API_common.php");
/*
 * This searches for fluxes matching string and returns a list of fluxes.
 * 
 * Input:
 * - name (the string to search in the name)
 * - email (the string to search in the email)
 */

if (isset($_GET['name'])) {
    $result = search_flux($_GET['name']);
} else if (isset($_GET['email'])) {
    $result = search_email($_GET['email']);
}
print_formatted_result($result, $format,$callback);

function search_flux($string) {
    $db = db_connect();
    $string = mysql_real_escape_string($string);
    $query = "SELECT * FROM fluxes WHERE name LIKE '$string%' LIMIT 10";
    $result = mysql_query($query);
    if (!$result) {die("XP query:".$query." error:".mysql_error());}
    $rows = array();
    while($r = mysql_fetch_assoc($result)) {
            array_push($rows,$r);
    }
    return $rows;
}

function search_email($string) {
    $db = db_connect();
    $string = mysql_real_escape_string($string);
    $query = "SELECT f.flux_id, f.name, f.userflux, u.email FROM fluxes AS f,users AS u WHERE u.email LIKE '$string%' AND f.owner = u.user_id AND f.userflux='1' LIMIT 10";
    $result = mysql_query($query);
    if (!$result) {die("XP query:".$query." error:".mysql_error());}
    $rows = array();
    while($r = mysql_fetch_assoc($result)) {
            array_push($rows,$r);
    }
    return $rows;
}

?>
