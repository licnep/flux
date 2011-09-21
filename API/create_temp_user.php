<?php
require_once(dirname(__FILE__)."/API_common.php");

$db = db_connect();
$randUsername = "Guest".uniqid();
$password = uniqid();

require_once(dirname(__FILE__)."/phpAPI/phpAPI.php");
$result = flux_api_call("register_account.php?username=$randUsername&password=$password&temp=1");
if (!$result) {die("Error, call:")."register_account.php?username=$randUsername&password=$password&temp=1";}

$hash = md5(md5($password).md5($randUsername));
$call = "check_login.php?username=$randUsername&hash=$hash".
        (isset($_GET['callback'])?"&callback=".$_GET['callback']:"").
        (isset($_GET['format'])?"&format=".$_GET['format']:"");

$json = flux_api_call($call);
echo $json;

?>
