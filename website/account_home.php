<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<?php include(dirname(__FILE__).'/include/phpTOP.php');?>
<html>
<head>
	<!--vvIMPORTANT! charset must be utf8 for box2d-->	
	<meta http-equiv="Content-Type" content="text/html; charset = UTF-8" /> 
	<!--jqueryUI's css, not really necessary:-->
	<link href="include/jquery/css/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
	<link href="css/main.css" rel="stylesheet" type="text/css"/>	
        <!--[BOOTSTRAP] the bootstrap style, then we launch less.js to compile it-->
        <link rel="stylesheet/less" type="text/css" href="css/bootstrap/lib/bootstrap.less">
        <script src="css/bootstrap/less.js" type="text/javascript"></script>
        <!--[/BOOTSTRAP]-->
        
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
<!--[MODAL POPUP STUFF]-->
<script src="css/bootstrap/js/bootstrap-modal.js" type="text/javascript"></script>
<script type="text/javascript">
	function popup(url) {
            $.get(url, '', popupCallback, 'html');
        }
        function popupCallback(data) {
            dialog = $('<div class="modal hide fade"></div>').appendTo('body');
            dialog.html(data).modal({backdrop: true});
            dialog.modal('show');
            dialog.bind('hidden',function() {$('.modal').remove()});
        }
        //$(document).ready( function() {popup('include/login.php');});
</script>
<!--[/MODAL POPUP STUFF]-->
</head>
<body>
<?php include('include/topBar.php');?>
<div class="container">
<!-- if he's a temporary user we show the warning bar-->
<?php
if ($_SESSION['temp']==1) {
?>
<div class="alert-message warning">
    WARNING, logged in as Guest, <a onclick="popup('include/register.php')" href="#">register</a> or <a onclick="popup('include/login.php')" href="#">login</a> to save your changes.
</div>
<?php } ?>
    
<!-- Deprecated. The searchbar will be included in the topbar 
<div id="searchbox">
    Search fluxes:<br/>
    <input id="searchbar" type="text" class="search" name="search" value="insert keyword or email" size="30"/>
    <div id="results"></div>
</div>
-->
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
                    flux_api_call(
                        "pool/create_transaction_key.php?key="+_session["hash"]+"&pool_id=1&flux_to_id="+id,
                        function(json) {
                            transaction_key = json;
                            window.location = "../pools/paypalPool/donate.php?transaction_key="+transaction_key;
                        }
                    );
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
}

/*this is called once the API responds with the amount of money in the user account*/
function gotUserBalance(json) {
    $("#myMoney").html(json);
}

/*Once the document is ready we actually populate the page with some data:*/
$(document).ready(function() {
    /*we retrieve the list of the fluxes owned by the user to populate his page.*/
    flux_api_call("get_fluxes_owned_by.php?user_id="+_session["uid"],gotFluxList);
    /*we retrieve the id of the user's personal flux to show it at the bottom of the page*/
    flux_api_call("get_user_flux_ID.php?user_id="+_session["uid"],gotUserFluxID);
    /*we also make the api call (which calls the pool), to see how much money the user has*/
    flux_api_call("get_user_balance.php?pool_id=1&user_id="+_session['uid'],gotUserBalance);
    /*Initialize the withdraw button:*/
    $('#withdrawBtn').click(function() {
        flux_api_call(
            "pool/start_withdrawal_get_key.php?key="+_session['hash'],
            function(json) {
                transaction_key = json;
                window.location = "../pools/paypalPool/withdraw.php?transaction_key="+transaction_key;
            }
        );
    });
});
</script>

<h2>My Account:</h2>
<span id="myFlux" class="blueBox draggable" flux_id="FLUXID" name="USERNAME" description="---">USERNAME</span><span class="blueBox"><span id="myMoney">MONEY</span> <a id="withdrawBtn" href="#">Withdraw</a></span>
<br/>
</div>
</body>
</html>
