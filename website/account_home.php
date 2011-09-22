<?php include(dirname(__FILE__).'/include/phpTOP.php');
	//if he's not logged in redirect to the login window 
	//^ this will be changed in the future, even non logged in users can log in
	if (!($_SESSION['logged'])) {
    	header("location: login.php");
	}
?>
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
<script type="text/javascript" src="include/searchBox.js"></script>
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
<div id="searchbox">
    Search fluxes:<br/>
    <input id="searchbar" type="text" class="search" name="search" value="insert keyword or email" size="30"/>
    <div id="results"></div>
</div>
<h1>[This is your personal page. Welcome]</h1>

<h2>My FLUXES:</h2>
<div id="myfluxes">
</div>
<span class="blueBox"><a href="create_flux.php">+ Create a new flux</a></span>

<script type="text/javascript">

/*this callback is called once we get the list of fluxes owned by the user from the API*/
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
                    "pool/create_transaction_key.php?key="+_session["hash"]+"&pool_id=1&flux_to_id="+id);
                });
                /*create the cool container, automatically populating it with all the cool shit (subfluxes...)*/
		var tmp = new FluxContainer($("<div class=\"fluxContainer\"></div>").appendTo("#myfluxes"),id);
	}	
}

/*this callback is called once we get the id of the user's personal flux*/
function gotUserFluxID(json) {
    $("#myFlux")
        .attr("flux_id",json["flux_id"])
        .attr("name", _session["username"])
        .html(_session["username"]);
    $("#myMoney").html(json["money"]);
}

/*Once the document is ready we actually populate the page with some data:*/
$(document).ready(function() {
    /*we retrieve the list of the fluxes owned by the user to populate his page.*/
    flux_api_call(gotFluxList,"get_fluxes_owned_by.php?user_id="+_session["uid"]);
    /*we retrieve the id of the user's personal flux to show it at the bottom of the page*/
    flux_api_call(gotUserFluxID,"get_user_flux_ID.php?user_id="+_session["uid"]);
});
</script>

<h2>My Account:</h2>
<span id="myFlux" class="blueBox draggable" flux_id="FLUXID" name="USERNAME" description="---">USERNAME</span><span class="blueBox"><span id="myMoney">MONEY</span> $ <a href="hahaYoullneverGetThemMoney.exe">Withdraw</a></span>
<br/>
</body>
</html>
