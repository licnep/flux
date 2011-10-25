<?php
    /*
     * This webpage is called from javascript.
     * It tries to log the user in. 
     * Returns:
     * 'true' if the login is successfull or 'false' otherwise
     */
	//use the php session ($_SESSION)
	session_start();
	#FOR DEBUG: (enable error reporting)
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);

        if (!isset($_GET['username'])||!isset($_GET['password'])) {
            echo "false"; return;
        }
        
	$username = $_GET['username'];
	$password = $_GET['password'];
	$hash = md5(md5($password).md5($username));

	require_once('user_class.php');
	$user = new user();
	$user->_CheckLogin($username,$hash,true);
        session_write_close();
	//header('location: ../account_home.php');
        if ($_SESSION['logged']) {
            echo "true";
        } else {
            echo "false";
        }
?>
