<?php include("include/phpTOP.php");?><html>
<head>
	<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
	<script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
	<script type="text/javascript">
		function registrationCallback(array) {
			if (array==true) {
				/*yay registration is successful!*/
				window.location = "scripts/login.php?username="
                                        +$('input[name=username]').val()
                                        +"&password="+$('input[name=password]').val();
			} else {
				alert(array);
			}
		}

		$(document).ready( function() {
			$('#registrationForm').submit(function () {
				//preventing double submit:

				password = $('input[name=password]').val();
				username = $('input[name=username]').val();
                                email = $('input[name=email]').val();
				apiurl = "register_account.php?password="+password+"&username="+username+"&email="+email;
                                /*if it's a temporary user we add his details, so the API just upgrades his existing temp account*/
                                if (_session['temp']) {
                                    apiurl = apiurl+"&oldId="+_session['uid']+"&oldHash="+_session['hash'];
                                }
				flux_api_call(apiurl,registrationCallback);
				return false; //<- preventing form submit and page reload
			});
		})
	</script>
</head>
<body>
	<h1>REGISTER</h1>
	<form id="registrationForm">
		<p>Username: <input type="text" name="username" value="" /></p>
                <p>Email: <input type="text" name="email" value="" /></p>
		<p>Password: <input type="password" name="password" value="" /></p>
		<input type="submit" value="Register"/>
	</form>
</body>
</html>
