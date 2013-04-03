<?php

include('API_common.php');

include("LocalSettings.php");
$result = array('address'=>$C_paypal_pool_url);

print_formatted_result($result,$format,$callback);
?>
