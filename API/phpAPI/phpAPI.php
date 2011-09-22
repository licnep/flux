<?php
	/*This is the php API wrapper to easily call the API remotely using php*/

	function flux_api_call($call) {
		//$APIBASEURL = "http://flux.lolwut.net/flux/API/";
                include(dirname(__FILE__)."/../LocalSettings.php");
		$apiurl = $C_API_base_url . $call;
		return get_webpage($apiurl);
	}
        
/* gets the data from a URL */
function get_webpage($url)
{
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
        
?>
