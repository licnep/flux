<?php
include(dirname(__FILE__)."/API_common.php");

/*
 * This API call is used to find out the ID of the flux corresponding to a user account.
 * Every user has his very own flux, that is created automatically and cannot be deleted.
 * 
 * Input variables:
 * user_id
 */

$user_id = $_GET['user_id'];
$result = get_user_flux_ID($user_id);

print_formatted_result($result, $format, $callback);

function get_user_flux_id($user_id) {
    $db = db_connect();
    $query = "SELECT flux_id FROM fluxes WHERE
             owner='".mysql_real_escape_string($user_id)."' AND userflux=1";
    $result = mysql_query($query);
    if (!$result) {
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
    $row = mysql_fetch_array($result);
    $id = $row['flux_id'];
    return $id;
}

?>
