<?php
/*
This contains just stupid functions to make error handling better, and notify us.
*/

function E_notify($message) {
    
    $mail_From = "From: me@flux.lolwut.net";
    $mail_To = "lsnpreziosi@gmail.com";
    $mail_Subject = "Notification";
    $mail_Body = $message;
    
    mail($mail_To, $mail_Subject, $mail_Body, $mail_From);
}

function E_log($message) {
    $filename = dirname(__FILE__).'/log.txt';
    $date = date("d/m/y H:i:s");
    file_put_contents($filename,$date.'--'.$message."\n",FILE_APPEND);
}

?>