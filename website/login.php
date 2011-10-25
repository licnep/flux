<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<html>
<head>
    <!--[BOOTSTRAP] the bootstrap style, then we launch less.js to compile it-->
    <link rel="stylesheet/less" type="text/css" href="css/bootstrap/lib/bootstrap.less">
    <script src="css/bootstrap/less.js" type="text/javascript"></script>
    <!--[/BOOTSTRAP]-->
    <script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
    <script src="css/bootstrap/js/bootstrap-alerts.js" type="text/javascript"></script>
    <script type="text/javascript">
    $(document).ready( function() {
        $('#loginForm').submit(submitCallback);
    });
    
    function submitCallback() {
        //when the form is submitted we pass the value to the login script, which will return either 'true' or 'false''
        //the return value will be passed to the loginCallback function
        $.get("scripts/login.php", {"username":$('#username').val(),"password":$('#password').val()}, loginCallback,'text');
        return false;
    }
    
    function loginCallback(json) {
        if (json=="true") {
            window.location = 'account_home.php';
        } else {
            $('#error').fadeIn(1000);
        }
    }

    </script>
</head>
<body>
    <div class="container">
    <form id="loginForm" method="GET" action="scripts/login.php">
        <fieldset>
          <legend>Login</legend>
          <div class="clearfix">
            <label for="username">Username</label>
            <div class="input">
              <input class="xlarge" id="username" name="username" size="30" type="text">
            </div>
          </div><!-- /clearfix -->
          <div class="clearfix">
            <label for="password">Password</label>
            <div class="input">
              <input class="xlarge" id="password" name="password" size="30" type="password">
            </div>
          </div><!-- /clearfix -->
            <div id="error" class="alert-message error" style="display:none">Wrong username or password.</div>
          <div class="actions">
            <input type="submit" class="btn primary" value="Login">&nbsp;or <a href="register.php">Register</a>
          </div>
        </fieldset>
      </form>
    </div>
</body>
</html>
