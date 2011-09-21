<?php
	/*This is the php API wrapper to easily call the API remotely using php*/

	function flux_api_call($call) {
		//$APIBASEURL = "http://flux.lolwut.net/flux/API/";
                include(dirname(__FILE__)."/../LocalSettings.php");
		$apiurl = $C_API_base_url . $call;
		//making the remote call with curl:
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiurl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
?>
