<?php
/*
 * flux_id - the flux to modify
 * name - the name of the property to change
 * value - the value of the property
 */
include('API_common.php');

$result = change_flux_opt($_GET['flux_id'], $_GET['name'], $_GET['value']);
print_formatted_result($result, $format,$callback);

function change_flux_opt($flux_id, $name, $value) {
    $db = db_connect();
    $flux_id = mysql_real_escape_string($flux_id);
    $name = mysql_real_escape_string($name);
    $value = mysql_real_escape_string($value);
    //get the old json object:
    $query = "SELECT opt FROM fluxes WHERE flux_id='$flux_id'";
    $result = mysql_query($query);
    if (!$result) {
        die('mysql error, query: '.$query.' error:'.mysql_error());
    }
    $row = mysql_fetch_array($result);
    $opt = json_decode(base64_decode(($row['opt'])));
    //now we have the old opt object, we edit the property and put it back in the db:
    $opt->{$name} = $value;
    $json_opt = base64_encode(json_encode($opt));
    $query = "UPDATE fluxes SET opt='$json_opt' WHERE flux_id='$flux_id'";
    $result = mysql_query($query);
    if (!$result) {
        die('mysql error, query: '.$query.' error:'.mysql_error());
    }
    return $opt;
}

?>
