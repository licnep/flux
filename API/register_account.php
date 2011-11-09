<?php

/*
 * Input:
 * - username
 * - password
 * - email
 * - [temp] #only used internally, set to 1 to register a temporary user
 * - [plaintext] #if set to one we save also the plaintext password, this is used only for email-only accounts that must still be confirmed
 * - [oldId] 
 * - [oldHash]
 * 
 * The variables oldId and oldHash must be set in the case you're not really creating a new user
 * but just turning a temporary user into a permanent one.
 * 
 */

include('API_common.php');

$username = $_GET['username'];
$password = $_GET['password'];

isset($_GET['email'])? $email=$_GET['email']:$email="";
isset($_GET['temp'])? $temp=1:$temp=0;
isset($_GET['plaintext'])? $plaintext=1:$plaintext=0;
if (isset($_GET['oldId'])&&isset($_GET['oldHash'])) {
    //we're just upgrading a temporary account:
    $result = upgrade_temp_account($username,$password,$email,$_GET['oldId'],$_GET['oldHash']);
} else {
    //it's a new account from scratch:
    $result = create_account($username,$password,$email,$temp,$plaintext);
}

print_formatted_result($result,$format,$callback);

/**
 *  This only puts the data in the database, but you still have to confirm
 *  in order to activate the account.
 *  
 */
function create_account($username,$password,$email,$temp=0,$plaintext=0) {

	$db = db_connect();

	$username = mysql_real_escape_string($username);
	$hash = md5(md5($password).md5($username));
        $temp = mysql_real_escape_string($temp);
        $email = mysql_real_escape_string($email);
        
        /*let's check if the username is already in use*/
        if (username_exists($username)) return "error: duplicated username";
        
	$query = "INSERT INTO users SET ".
			" username='$username', hash = '$hash', temp='$temp', email='$email' ".
                ($plaintext?",plaintextPWD='".mysql_real_escape_string($password)."'":"");

	$result = mysql_query($query,$db);
    if(!$result) {
        //query failed
		//TODO do something here
        die("query failed, query: ".$query."\n error:".mysql_error());
    }

	//create the users'flux, this flux represents the user account, it only accumulates money
	//it cannot have receivers
	
	//this returns the last autoincrement id, that is the id of the just inserted user
	//are we sure this works and returns the new user's id 100% of the times??
	$id = mysql_insert_id();
        $result = create_user_flux($id,$username);
//	$query = "INSERT INTO fluxes SET owner=$id, userflux=1, ".
//		"name='$username', description=''";
//
//	$result = mysql_query($query,$db);
        if(!$result) {
            //TODO do something here
            die("failed to create userflux");
        }

	return $result;
}
		
function upgrade_temp_account($username,$password,$email,$oldId,$oldHash) {
    $db = db_connect();
    $oldId = mysql_real_escape_string($oldId);
    $oldHash = mysql_real_escape_string($oldHash);
    $username = mysql_real_escape_string($username);
    $password = mysql_real_escape_string($password);
    $email = mysql_real_escape_string($email);
    
    /*let's check if the username is already in use*/
    if (username_exists($username)) return "error: duplicated username";
    
    /*
     * 1) we check that the oldId and hash are correct 
    */
    $query = "SELECT * FROM users WHERE 
        user_id='$oldId' AND
        hash='$oldHash'";
    $result = mysql_query($query);
    if (!$result) {die("Error: query:".$query." error:".mysql_error());}
    if (mysql_num_rows($result)!=1) {
        return false;
    }
    /*
     * 2) if we're here the user has proven to be himself, so we can upgrade 
     *    his temporary account.
     */
    $query = "UPDATE users 
        SET username='$username',
        email='$email',
        hash='". md5(md5($password).md5($username)) ."',
        temp=0
        WHERE user_id='$oldId'";
    $result = mysql_query($query);
    if (!$result) {die("Error. Query:".$query." error:".  mysql_error());}
    /*
     * 3) we also update the name of his 'userflux', whic was 'guestSomething'
     */
    $query = "UPDATE fluxes SET name='$username' WHERE owner='$oldId' AND userflux=1 OR userflux=2";
    $result = mysql_query($query);
    if (!$result) {die("Error updating the userflux name.");}
    return $result;
}

/**
 *
 * @param username string
 * @return true if the username is already in the db, false otherwise
 */
function username_exists($username) {
    $db = db_connect();
    $username = mysql_real_escape_string($username);
    $query = "SELECT username FROM users WHERE username='$username'";
    $result = mysql_query($query);
    if(!$result) { die("query failed, query: ".$query."\n error:".mysql_error());}
    else if (mysql_num_rows($result)!=0) {return true;}
    return false;
}

/**
 * This function, given a user id, creates the two layers of 'userfluxes'.
 * Basically when someone donates to a user the donation goes to the userflux of layer 2, which by default, redirects all the amount
 * to the userflux of layer 1 (the actual user account, kind of like a bank account).
 * A user can modify the way donations towards him are routed, by modifying the userflux of level 2.
 * The userflux of level 1 (the actual account), is 'private' and unmodifyable. All the money that arrives to a userflux of level 1 
 * stays in the userflux of level 1
 * 
 * @param string $uid 
 * @param strin $username
 */
function create_user_flux($uid,$username) {
    $db = db_connect();
    $uid = mysql_real_escape_string($uid);
    $username = mysql_real_escape_string($username);
    //create the 'private', un-redirectable, userflix of level one. Basically the user's bank account
    $query = "INSERT INTO fluxes SET owner='$uid', userflux=1, ".
            "name='$username'";
    $result = mysql_query($query);
    $userflux1_id = mysql_insert_id();
    if (!$result) {die("Error: query:".$query." error:".mysql_error());}
    //creating the userflux of level 2. The ones people see, and that is redirectable
    $query = "INSERT INTO fluxes SET owner=$uid, userflux=2, ".
            "name='$username'";
    $result = mysql_query($query);
    $userflux2_id = mysql_insert_id();
    if (!$result) {die("Error: query:".$query." error:".mysql_error());}
    //connect the public userflux, with the private 'bank account'.
    //by default all money donated to the user goes in his account, but he can change that
    $query = "INSERT INTO routing SET flux_from_id='$userflux2_id', flux_to_id='$userflux1_id', share='100'";
    $result = mysql_query($query);
    if (!$result) {die("Error: query:".$query." error:".mysql_error());}
    return true;
}