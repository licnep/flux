<html>
<head>
	<!--vvIMPORTANT! charset must be utf8 for box2d-->	
	<meta http-equiv="Content-Type" content="text/html; charset = UTF-8" /> 
	<!--jqueryUI css, not really necessary:-->
	<link href="include/jquery/css/vader/jquery-ui.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<h1>[This is your persoanl page. Welcome]</h1>
<h2>My FLUX:</h2>
<?php include("include/paypal_donate_button.php"); ?>
<!--<iframe src="../API/get_flux_info.php?flux_id=1"></iframe>-->
<!--<div id="canvas_container" style="width:400px;height:400px">
<div id="my_flux_canvas" style="width:400px;height:400px;position:absolute"></div> 
</div>-->
<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="include/jquery/jquery-ui.min.js"></script>

<script src="include/bubbles/protoclass.js"></script>
<script src="include/bubbles/box2djs.min.js"></script>
<script  type="text/javascript" src="include/bubbles/bubbles.js"></script>

<script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
<script type="text/javascript" src="include/horizontal_flux/horizontal_flux.js"></script>


<div id="container1" style="width:100%;height:108px;background-color:#202020;"></div>

<script type="text/javascript">
//############OLD: just some tests:
/* function got_my_flux_info(array) {
	var asd = new BubbleCanvas(document.getElementById('my_flux_canvas'));
	for (var i=0;i<array.length;i++) {
		console.log('FROM:'+array[i]['flux_from_id']+" TO:"+array[i]["flux_to_id"]+" share:"+array[i]['share']);
		asd.addBubble(array[i]['share']/10);
	}
	//start the simulation:
	setInterval( asd.step, 1000 / 40 );
}
 //getting the fluxes with user_id=1 and calling an inline function
 //get_fluxes_owned_by(got_my_flux_info,1);
  flux_api_call(got_my_flux_info,"http://localhost/API/get_flux_info.php?flux_id=1");*/
//############END OLD

//var hf = new HorizontalFlux(document.getElementById("container1"),1);
var hf = new HorizontalFlux("container1",1);
//start the bubble animation:
function animatePipes() {
	hf.step();
}
setInterval( animatePipes, 1000 / 8 );
</script>


<h2>Account Balance:</h2>
You have 0.00 $ <input type="submit" value="Withdraw"/>
<h2>My projects:</h2>
<iframe src="../API/get_fluxes_owned_by.php?user_id=1"></iframe>
<br/>
<a href="new_projectphp">New project</a>
</body>
</html>
