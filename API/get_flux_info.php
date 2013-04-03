<?php

include('API_common.php');
$flux_id = $_GET['flux_id'];

$result = get_flux_info($flux_id,$format);
$rows = array();
$total = 0;
while($r = mysql_fetch_assoc($result)) {
	array_push($rows,$r);
        $total += $r['share'];
}
$result = get_name_desc($flux_id);
$row  = mysql_fetch_array($result);

$object = (object)array('flux_id'=>$row['flux_id'],
                        'name' => $row['name'],
                        'opt' => (json_decode(stripslashes(base64_decode($row['opt'])))), 
                        'total'=> $total,
                        'userflux' =>  $row['userflux'],
                        'children' => $rows);

require_once("print_formatted_result.php");
print_formatted_result($object,$format,$callback);

function get_flux_info($flux_id) {
    require_once('execute_query.php');
    $db = db_connect("flux_changer");
    $query = "SELECT flux_from_id, flux_to_id, share, name, opt, userflux
                    FROM fluxes, routing
                    WHERE routing.flux_from_id='".mysql_real_escape_string($flux_id).
                    "' AND routing.flux_to_id=fluxes.flux_id ORDER BY routing.share DESC";
    $result = mysql_query($query,$db);
    if(!$result) {//TODO something
            die("query failed, query: ".$query."\n error:".mysql_error());
    }
    return $result;
}

function get_name_desc($flux_id) {
    require_once('execute_query.php');
    $db = db_connect("flux_changer");
    $query = "SELECT f.flux_id,f.name,f.opt,f.userflux FROM fluxes AS f WHERE f.flux_id='".mysql_real_escape_string($flux_id)."'";
    $result = mysql_query($query,$db);
    if(!$result) {//TODO something
            die("query failed, query: ".$query."\n error:".mysql_error());
    }
    return $result;
}

?>
