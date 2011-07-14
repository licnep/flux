<?php
	/*This is the php API wrapper to easily call the API remotely using php*/

	function flux_api_call($call) {
		$APIBASEURL = "http://localhost/API/";	
		$apiurl = $APIBASEURL . $call;
		//making the remote call with curl:
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiurl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}
?>
