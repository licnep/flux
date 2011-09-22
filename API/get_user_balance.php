<?php
require_once(dirname(__FILE__)."/API_common.php");
/*
 * Input:
 * -user_id
 * -pool_id (needed when we'll have multiple pools, currently unused, always set to 1)
 * 
 * Returns:
 * the amount of 'money' in the user account.
 */


$db = db_connect();
/*
 * 1) We find the total amount of money in the db.
 */
$result = mysql_query("SELECT SUM(money) AS total FROM fluxes");
if (!$result) die("Error:".  mysql_error());
$row = mysql_fetch_array($result);
$total = $row['total'];
/*
 * 2) We find the amount of money in the user account.
 */
$user_id = mysql_real_escape_string($_GET['user_id']);
$result = mysql_query("SELECT money FROM fluxes WHERE owner='$user_id' AND userflux=1");
if (!$result) die(mysql_error());
$row = mysql_fetch_array($result);
$amount = $row['money'];

/*
 * TODO: this needs to be changed, but for now we're just testing so let's put the relative pool address on here.
 */
$result = get_webpage("http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."/../../pools/lovePool/convert.php?amount=$amount&total=$total");
print_formatted_result($result, $format, $callback);

?>
