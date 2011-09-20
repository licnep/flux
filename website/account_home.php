<?php include('include/phpTOP.php'); ?>
<?php
	//if he's not logged in redirect to the login window 
	//^ this will be changed in the future, even non logged in users can log in
	if (!($_SESSION['logged'])) {
    	header("location: login.php");
    	exit();
	}
?>
<script type="text/javascript">
<?php echo 'var _session = '.json_encode($_SESSION).";\n"; ?>
</script>
<html>
<head>
	<!--vvIMPORTANT! charset must be utf8 for box2d-->	
	<meta http-equiv="Content-Type" content="text/html; charset = UTF-8" /> 
	<!--jqueryUI's css, not really necessary:-->
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

<h2>My FLUXES:</h2>
<div id="myfluxes">
</div>
<span class="blueBox"><a href="create_flux.php">+ Create a new flux</a></span>

<script type="text/javascript">

function gotFluxList(json) {
    /*For each fluc owned by the user:*/
	for (var i=0;i<json.length;i++) {
		id = json[i]["flux_id"];
		titleSpan = $('<span class="blueBox draggable">'+json[i]["name"]+"</span>").appendTo("#myfluxes").draggable({revert:'invalid',connectToSortable:'.receiversUL',helper:'clone'});
		titleSpan.attr("flux_id",id);
		titleSpan.attr("name",json[i]["name"]);
		titleSpan.attr("description",json[i]["description"]);
        /*create the "donate" button*/
		donateBtn = $("<span class=\"blueBox\"><a href=\"#\">DONATE</a></span>").appendTo("#myfluxes");
        /*when the donate button is clicked we do an API call to get a transaction key*/
        donateBtn.click(function() {
            flux_api_call(function(json) {
                transaction_key = json;
                window.location = "../pools/lovePool/sendLove.php?transaction_key="+transaction_key;
            },
            "pool/create_transaction_key.php?key="+_session["hash"]+"&pool_id=1");
        });
        /*create the cool container, automatically populating it with all the cool shit (subfluxes...)*/
		var tmp = new FluxContainer($("<div class=\"fluxContainer\"></div>").appendTo("#myfluxes"),json[i]["flux_id"]);
	}	
}
$(document).ready(flux_api_call(gotFluxList,"get_fluxes_owned_by.php?user_id="+_session["uid"]));
</script>

<h2>My Account:</h2>
<span class="blueBox draggable" flux_id="712739" name="Licnep" description="---">Licnep</span><span class="blueBox">0.00 $ <a href="hahaYoullneverGetThemMoney.exe">Withdraw</a></span>
<br/>
</body>
</html>
