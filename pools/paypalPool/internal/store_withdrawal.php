<?php
require_once(dirname(__FILE__).'/common/error_handling.php');
/*
This script is called once we get the IstantPaymentNotification from paypal, to store the transaction in our db.
This script can only be called locally.
*/

function store_withdrawal($key, $amount, $successful) {
    require_once("db_connect.php");
    $db = db_connect();
    $query = "INSERT INTO transactions SET transaction_id='".mysql_real_escape_string($key).
         "', amount=".mysql_real_escape_string($amount).", type=1, ack=0, successful='".
         mysql_real_escape_string($successful)."'";
     $result = mysql_query($query,$db);
    if(!$result) {
        E_notify("Saving withdrawal transaction FAILED, query: ".$query."\n error:".mysql_error());
        return false;
    }
    //if we're here we stored the transaction correctly, now we'll repeatedly notify the flux backend until we get a response it has aknowledged the transaction
    //For now we just do it once, right after we have stored the transaction. So let's do it. Let's notify the flux api:
    require_once(dirname(__FILE__).'/send_withdrawal_acks.php');
    send_withdrawal_acks();
    
    return true;
}
?>