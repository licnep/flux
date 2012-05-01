<?php
$head='';
$body='';

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<!--[JS VALIDATION]-->
<script type="text/javascript" src="include/jquery/jquery.validate.min.js"></script>
<!--[/JS VALIDATION]-->
<div class="well">
    <form id="registrationForm" style="margin-bottom: 0px">
    <fieldset>
        <div class="modal-header">
            <legend>Register</legend>
        </div>
        <div class="modal-body">
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
      </div>
    </fieldset>
    <div class="modal-footer">
        <div style="padding-left:150px">
            <input type="submit" class="btn primary" value="Submit">
        </div>
    </div>
    </form>
</div>
<script type="text/javascript">
    
    var validator;
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
        console.log('callback');
        console.log(array);
        if (array['user_id']!=undefined) {
            /*yay registration is successful!*/
            
            //trying to save the default userflux description
            flux_api_call("get_user_flux_ID.php?user_id="+array['user_id'],function(data) {
                description = "##This is your personal flux, it represents you as a person\n\nBy default all donations to your flux are redirected to your account, but if you feel generous you you can redirect them to other projects";
                flux_api_call("change_flux_opt.php?flux_id="+data['flux_id']+'&name=desc&value='+encodeURIComponent(description), function() {});
            });
            
            url = "scripts/login.php?username="
                +$('input[name=username]').val()
                +"&password="+$('input[name=password]').val();
            $.get(url, '', function() {window.location='account_home.php';}, 'text');
        } else {
            if (array=="error: duplicated username") {
                validator.showErrors({'username':'Username already in use'});
            }
        }
    }
    
    $(document).ready( function() {
            $('#username').focus();
        }
    );

</script>
<?php
$body .= ob_get_clean();

//START TOOLTIPS/POPOVERS (we attach the required js files to the header)
ob_start(); ?>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-twipsy.js"></script>
<script type="text/javascript" src="css/bootstrap/js/bootstrap-popover.js"></script>
<?php
$head .= ob_get_clean();
//END TOOLTIPS/POPOVERS

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>