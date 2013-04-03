<?php

function send_email_someone_is_trying_to_donate_to_your_email($email,$password) {
    $subject = 'Someone wants to donate to your email';
    $body = "Yo yo yo,
someone wants to donate money to this email address. 
If you wish to withdraw the donation login here:
http://flux.lolwut.net/flux/website/login.php

Temporary credentials:
email: $email
password: $password
";
    send_flux_mail($email,$subject,$body);
}

function send_flux_mail($receiver,$subject,$body) {
    $header = "From: no-reply@flux.lolwut.net";
    mail($receiver,$subject,$body,$header);
}

?>