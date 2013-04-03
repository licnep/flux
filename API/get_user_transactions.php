<?php
require_once(dirname(__FILE__)."/API_common.php");
/*
 * Input:
 * -user_id
 * 
 * Returns:
 * The list of all the user's transactions
 */

if (!isset($_GET['user_id'])) {
    $result = "error:user_id not defined";
    print_formatted_result($result, $format, $callback);
    return;
}
$result = get_user_transactions($_GET['user_id']);
print_formatted_result($result, $format, $callback);

function get_user_transactions($user_id) {
    $db = db_connect();
    $user_id = mysql_real_escape_string($user_id);
    $query = "SELECT * FROM transactions AS t LEFT JOIN fluxes AS f ON t.flux_to_id=f.flux_id WHERE user_id='$user_id' AND status=1";
    $result = mysql_query($query);
    if (!$result) {
        return "error in the query:".$query.' error:'.mysql_error();
    } 
    //convert the results to an array
    $rows = array();
    while($r = mysql_fetch_assoc($result)) {
            array_push($rows,$r);
    }
    return $rows;
    
}

?>
