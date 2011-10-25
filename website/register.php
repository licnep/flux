<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> 
<?php include("include/phpTOP.php");?><html>
<head>
        <!--[BOOTSTRAP] the bootstrap style, then we launch less.js to compile it-->
        <link rel="stylesheet/less" type="text/css" href="css/bootstrap/lib/bootstrap.less">
        <script src="css/bootstrap/less.js" type="text/javascript"></script>
        <!--[/BOOTSTRAP]-->
	<script type="text/javascript" src="include/jquery/jquery-1.6.2.min.js"></script>
        <script type="text/javascript" src="../API/javascriptAPI/fluxAPI.js"></script>
        <!--[JS VALIDATION]-->
        <script type="text/javascript" src="include/jquery/jquery.validate.min.js"></script>
        <script type="text/javascript">
            var validator;
            $(document).ready( function() {
                validator = $('#registrationForm').validate( {
                    rules: {
                        username: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: "true"
                        },
                        password: "required",
                        password2: {
                            equalTo: "#password"
                        }
                    },
                    messages: {
                        username: {
                            required: "This field is required"
                        },
                        email: {
                            required: "This field is required",
                            email: "Must be a valid email address"},
                        password: {required: "This field is required"},
                        password2: "The passwords are different"
                    },
                    /*we change the error highlight and unhighlight methods to work with the bootstrap style*/
                    highlight: function (element, errorClass, validClas) { 
                        $(element).parents("div[class='clearfix']").addClass("error"); 
                    }, 
                    unhighlight: function (element, errorClass, validClass) { 
                        $(element).parents(".error").removeClass("error"); 
                    },
                    errorElement: "span",
                    errorClass: "help-inline",
                    submitHandler: submitCallback
                });
            });
            
            function submitCallback() {
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
            }
            
            function registrationCallback(array) {
                if (array==true) {
                    /*yay registration is successful!*/
                    window.location = "scripts/login.php?username="
                        +$('input[name=username]').val()
                        +"&password="+$('input[name=password]').val();
                } else {
                    if (array=="error: duplicated username") {
                        validator.showErrors({'username':'Username already in use'});
                    }
                }
            }

        </script>
        <!--[/JS VALIDATION]-->
</head>
<body>
        
        <form id="registrationForm">
        <fieldset>
          <legend>Register</legend>
          <div class="clearfix">
            <label for="username">Username</label>
            <div class="input">
              <input class="xlarge" id="username" name="username" size="30" type="text">
            </div>
          </div><!-- /clearfix -->
          <div class="clearfix">
            <label for="email">Email</label>
            <div class="input">
              <input class="xlarge" id="email" name="email" size="30" type="text">
            </div>
          </div><!-- /clearfix -->
          <div class="clearfix">
            <label for="password">Password</label>
            <div class="input">
              <input class="xlarge" id="password" name="password" size="30" type="password">
            </div>
          </div><!-- /clearfix -->
          <div class="clearfix">
            <label for="password2">Repeat password</label>
            <div class="input">
              <input class="xlarge" id="password2" name="password2" size="30" type="password">
            </div>
          </div><!-- /clearfix -->
          <div class="actions">
            <input type="submit" class="btn primary" value="Submit">&nbsp;<button type="reset" class="btn">Cancel</button>
          </div>
        </fieldset>
      </form>
</body>
</html>
