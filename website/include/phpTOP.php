<?php
/*
 * php_TOP must be put on top of each page, and it
 * - checks the login
 * - checks the language (not yet)
 */

//queste cose servono per controllare il login
session_start();
require_once (dirname(__FILE__).'/../scripts/user_class.php');

//calling "user= new user" it checks the session and the cookie and sets _SESSION['logged']
$user = new user();
session_write_close();
//passing the user variables to javascript, in case it's needed in the page:
echo "<script type=\"text/javascript\">\n";
echo 'var _session = '.json_encode($_SESSION).";\n";
echo 'var _get = '.json_encode($_GET) .";\n";
echo "</script>\n";
?>