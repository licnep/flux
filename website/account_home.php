<html>
<head>
	<!--vvIMPORTANT! charset must be utf8 for box2d-->	
	<meta http-equiv="Content-Type" content="text/html; charset = UTF-8" /> 
	<!--jqueryUI css, not really necessary:-->
	<link href="include/jquery/css/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<link href="include/horizontal_flux/fluxContainer.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<h1>[This is your personal page. Welcome]</h1>
<?php include("include/paypal_donate_button.php"); ?>
<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="include/jquery/jquery-ui.min.js"></script>

<script src="include/bubbles/protoclass.js"></script>
<script src="include/bubbles/box2djs.min.js"></script>
<script  type="text/javascript" src="include/bubbles/bubbles.js"></script>

<script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
<script type="text/javascript" src="include/horizontal_flux/fluxContainer.js"></script>

<h2>My FLUXES:</h2>
<div id="myfluxes"></div>

<script type="text/javascript">

//var horizontalFluxes = new Array();

function gotFluxList(json) {
	for (var i=0;i<json.length;i++) {
		id = json[i]["flux_id"];
		$("<span class=\"blueBox\">"+json[i]["name"]+"</span>").appendTo("#myfluxes").draggable({revert:true});
		$("<span class=\"blueBox\">DONATE</span>").appendTo("#myfluxes");
		var tmp = new FluxContainer($("<div class=\"fluxContainer\"></div>").appendTo("#myfluxes"),json[i]["flux_id"]);
	}	
}
$(document).ready(flux_api_call(gotFluxList,"http://localhost/API/get_fluxes_owned_by.php?user_id=1"));
</script>

<h2>My Account:</h2>
<span class="blueBox">Licnep</span><span class="blueBox">0.00 $ <a href="hahaYoullneverGetThemMoney.exe">Withdraw</a></span>
<br/>
</body>
</html>
