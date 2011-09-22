<?php
require_once(dirname(__FILE__)."/API_common.php");

/*
 * There are 2 kinds of temp users we can create.
 * 1) the GUESTS, which are temporary user accounts for people who just came to the website
 * 2) the 'EMAILONLY', which are temporary accounts made when you donate to an email, 
 *  the owner of the email will then have to log in the temporary account and register it as permanent
 * 
 * Input:
 * [email] #only if you're creating a temporary email-only account
 */

$db = db_connect();

if (!isset($_GET['email'])) {
    /*make a regular Guest account*/
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
} else {
    /*we have an email, make an email-only temporary account*/
    $result = make_email_only_account($_GET['email']);
    print_formatted_result($result, $format, $callback);
}

function make_email_only_account($email) {
    $email = mysql_real_escape_string($email);
    //a 'random' password
    $password = uniqid();
    //make the API call to create the user account:
    require_once(dirname(__FILE__)."/phpAPI/phpAPI.php");
    $apicall = "register_account.php?username=$email&password=$password&email=$email&temp=1&plaintext=1";
    $result = flux_api_call($apicall);
    if (!$result) {die("error, call=".$apicall);}
    
    
    return $result;
}

?>
