<?php
require_once('user_class.php');
session_start();
user_logout();
$goto = "localhost/website/login.php";
if (!empty($_GET['goto'])) $goto = $_GET['goto'];
header("location: ".$goto);
?>
