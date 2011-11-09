<form id="loginForm" method="GET" action="scripts/login.php" style="margin-bottom: 0px">
    <fieldset>
        <div class="modal-header">
            <legend>Login</legend>
        </div>
        <div class="modal-body">
             <div id="error" class="alert-message error hide">Wrong username or password.</div>                
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
        </div><!--modal body-->
    </fieldset>
    <div class="modal-footer">
        <div style="padding-left:150px">
        <input type="submit" class="btn primary" value="Login">&nbsp;or <a onClick="switchToRegister()" href="#">Register</a>
        </div>
    </div>
</form>
<script type="text/javascript">
    $('.modal').bind('shown',function() {
            $('#username').focus();
    });
    
    $('#loginForm').submit(submitCallback);
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

//transform the modal dialog into a registration dialog
function switchToRegister() {
    $('.modal').bind('hidden',function() {
            $FW.popupUrl('include/popups/register.php');
    });
    $('.modal').modal('hide');
}

</script>
