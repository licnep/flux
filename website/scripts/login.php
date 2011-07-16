<?php
	//use the php session ($_SESSION)
	session_start();
	#FOR DEBUG: (enable error reporting)
	ini_set('display_errors',1);
	error_reporting(E_ALL|E_STRICT);


	//try to login to the remote API with some "curl"
	$username = $_GET['username'];
	$password = $_GET['password'];
	$hash = md5(md5($password).md5($username));

	require_once('user_class.php');
	$user = new user();
	$user->_CheckLogin($username,$hash,true);
	header('location: ../account_home.php');
?>
