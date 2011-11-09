<?php
include(dirname(__FILE__)."/API_common.php");

/*
 * This API call is used to find out the personal flux of some user.
 * 
 * Input variables:
 * user_id
 * 
 * Returns:
 * flux_id
 * money
 * 
 * TODO: this'll have to be changed cause in the final version we will have more than 1 money pool
 */

$user_id = $_GET['user_id'];
$result = get_user_flux_ID($user_id);

print_formatted_result($result, $format, $callback);

function get_user_flux_id($user_id) {
    $db = db_connect();
    $query = "SELECT flux_id,money FROM fluxes WHERE
             owner='".mysql_real_escape_string($user_id)."' AND userflux=2";
    $result = mysql_query($query);
    if (!$result) {
        die("query failed, query: ".$query."\n error:".mysql_error());
    }
    $row = mysql_fetch_array($result);
    return array("flux_id" => $row['flux_id'], "money" => $row["money"]);
}

?>
