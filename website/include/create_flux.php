<html>
<head>
	<script type="text/javascript" src="jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../../API/javascriptAPI/fluxAPI.js"></script>
	<script type="text/javascript">
		function registrationCallback(array) {
			if (array==true) {
				alert("Creation successful!");
				window.location = "../account_home.php";
			} else {
				alert(array);
			}
		}

		$(document).ready( function() {
			$('#creationForm').submit(function () {
				//preventing double submit:
				name = $('input[name=name]').val();
				description = $('input[name=description]').val();
				apiurl = "create_flux.php?name="+name+"&description="+description+"&user_id=1";
				flux_api_call(registrationCallback,apiurl);
				return false; //<- preventing form submit and page reload
			});
		})
	</script>
</head>
<body>
<h1>New flux:</h1>
<form id="creationForm">
<p>Name:<input type="text" name="name" value=""/></p>
<p>Short description:<input type="text" name="description" value=""/></p>
<input type="submit" value="Create">
</form>
This will be transformed in a popup, not shown as a web page, somone do this.
</body>
</html>
