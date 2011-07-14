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

				email = $('input[name=email]').val();
				password = $('input[name=password]').val();
				apiurl = "register_account.php?email="+email+"&password="+password
				flux_api_call(registrationCallback,apiurl);
				return false; //<- preventing form submit and page reload
			});
		})
	</script>
</head>
<body>
<h1>REGISTER</h1>
<form id="registrationForm">
<p>Email: <input type="text" name="email"value="" /></p>
<p>Password: <input type="text" name="password" value="" /></p>
<input type="submit" value="Register"/>
</form>
</form>
</body>
</html>
