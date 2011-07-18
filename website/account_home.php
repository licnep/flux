<?php include('include/phpTOP.php'); ?>
<html>
<head>
	<!--vvIMPORTANT! charset must be utf8 for box2d-->	
	<meta http-equiv="Content-Type" content="text/html; charset = UTF-8" /> 
	<!--jqueryUI css, not really necessary:-->
	<link href="include/jquery/css/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<link href="css/main.css" rel="stylesheet" type="text/css"/>	
	<link href="include/horizontal_flux/fluxContainer.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="include/jquery/jquery-ui.min.js"></script>
<script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
<script type="text/javascript" src="include/horizontal_flux/fluxContainer.js"></script>
<script type="text/javascript">
	$(document).ready( function() {
		$('.draggable').draggable({
			revert:'invalid', /*return to original position if dragged in an invalid area*/
			connectToSortable:'.receiversUL', /*can be dragged into a list of receivers*/
			helper: 'clone' /*not the original is dragged, but a clone*/
		});
   	});
</script>
</head>
<body>
<?php include('include/topBar.php');?>
<h1>[This is your personal page. Welcome]</h1>
<?php include("include/paypal_donate_button.php"); ?>

<h2>My FLUXES:</h2>
<div id="myfluxes"></div>

<script type="text/javascript">

//var horizontalFluxes = new Array();

function gotFluxList(json) {
	for (var i=0;i<json.length;i++) {
		id = json[i]["flux_id"];
		titleSpan = $("<span class=\"blueBox draggable\">"+json[i]["name"]+"</span>").appendTo("#myfluxes").draggable({revert:'invalid',connectToSortable:'.receiversUL',helper:'clone'});
		titleSpan.attr("flux_id",id);
		titleSpan.attr("name",json[i]["name"]);
		titleSpan.attr("description",json[i]["description"]);
		$("<span class=\"blueBox\"><a href=\"#\">DONATE</a></span>").appendTo("#myfluxes");
		var tmp = new FluxContainer($("<div class=\"fluxContainer\"></div>").appendTo("#myfluxes"),json[i]["flux_id"]);
	}	
}
$(document).ready(flux_api_call(gotFluxList,"get_fluxes_owned_by.php?user_id=1"));
</script>

<h2>My Account:</h2>
<span class="blueBox draggable" flux_id="712739" name="Licnep" description="---">Licnep</span><span class="blueBox">0.00 $ <a href="hahaYoullneverGetThemMoney.exe">Withdraw</a></span>
<br/>
</body>
</html>
