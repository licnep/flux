<script type="text/javascript" src="include/jquery/jquery.validate.min.js"></script>
<form id="creationForm" style="margin-bottom: 0px">
    <fieldset>
        <div class="modal-header">
            <legend>Create Flux</legend>
        </div>
        <div class="modal-body">
          <div class="clearfix">
            <label for="username">Name:</label>
            <div class="input">
              <input class="xlarge" id="name" name="name" size="30" type="text">
            </div>
          </div><!-- /clearfix -->
        </div><!--modal body-->
    </fieldset>
    <div class="modal-footer">
        <div style="padding-left:150px">
            <input type="submit" class="btn primary" value="Create" />
        </div>
    </div>
</form>
<script type="text/javascript">

        $CreateFlux = {
                submitCallback: function () {
                        name = $('input[name=name]').val();
                        description = $('input[name=description]').val();
                        apiurl = "create_flux.php?name="+name+"&description="+description+"&user_id="+_session['uid'];
                        flux_api_call(apiurl,$CreateFlux.registrationCallback);
                        return false; 
                },
                registrationCallback: function (array) {
                    if (array==true) {
                            window.location = "account_home.php";
                    } else {
                            alert(array);
                    }
                }
                
        }

        $('#creationForm').validate( {
            rules: {
                name: {
                    required: true
                }
            },
            messages: {
                name: {
                    required: "This field is required"
                }
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
            submitHandler: $CreateFlux.submitCallback
        });
        
        $('#name').parents('.modal').bind('shown', function () {
            $('#name').focus();
        });
</script>