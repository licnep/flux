<html>
<head>
	<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
	<script type="text/javascript">
		function registrationCallback(array) {
			if (array==true) {
				alert("Registration successful!");
				window.location = "login.php";
			} else {
				alert(array);
			}
		}

		$(document).ready( function() {
			$('#registrationForm').submit(function () {
				//preventing double submit:

				password = $('input[name=password]').val();
				username = $('input[name=username]').val();
				apiurl = "register_account.php?password="+password+"&username="+username;
				flux_api_call(registrationCallback,apiurl);
				return false; //<- preventing form submit and page reload
			});
		})
	</script>
</head>
<body>
	<h1>REGISTER</h1>
	<form id="registrationForm">
		<p>Username: <input type="text" name="username"value="" /></p>
		<p>Password: <input type="password" name="password" value="" /></p>
		<input type="submit" value="Register"/>
	</form>
</body>
</html>
