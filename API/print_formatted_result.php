<?php

function print_formatted_result($result,$format,$callback="") {
	//JSON:
	if ($format=="json") {
		/*
		$rows = array();
		while($r = mysql_fetch_assoc($result)) {
			array_push($rows,$r);
		}*/

		//if callback is set, we return a valid javascript statement otherwise just the plain json data
		if ($callback!='') {
			//we set the mime type to text/javascript otherwise chrome gives a warning
			header('Content-type: text/javascript');
			echo str_replace("%s",json_encode($result),$callback);
		}
		else echo json_encode($result);
	}
	//TODO other formats
}
?>
