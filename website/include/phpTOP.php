<?php
/*
 * php_TOP must be put on top of each page, and it
 * - checks the login
 * - checks the language (not yet)
 */

//queste cose servono per controllare il login
session_start();
require_once ('scripts/user_class.php');

//calling "user= new user" it checks the session and the cookie and sets _SESSION['logged']
$user = new user();
?>
