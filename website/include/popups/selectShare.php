<div class="modal-header">
    <h2>Choose the Amount:</h2>
</div>
<form id="shareform" style="margin-bottom: 0px">
    <div class="modal-body">
            <fieldset>
                <label class="span4" for="xnumber">Insert a number from 1 to 4 billions: </label>
                <div class="input-append">
                    <input class="xlarge span2" style="text-align:right" type="text" name="xnumber" id="xnumber" value="10" size="5" ></input>
                    <span class="add-on" id="xpercentage"></span>
                </div>
            </fieldset>
    </div>
    <div class="modal-footer" style="text-align:center">
        <input type="submit" class="btn primary" value="Confirm">
</div>
</form>
<script type="text/javascript">
    $ShareSelector = {
        total: $FW.popups.selectShare.data['total'],
        percentageElement: $('#xpercentage'),
        amountElement: $('#xnumber'),
        amount: 0
    }
    
    $ShareSelector.updatePercentage = function() {
        $ShareSelector.amount = parseInt($ShareSelector.amountElement.val(),10) || 0;
        amt = $ShareSelector.amount;
        var p = ((amt/($ShareSelector.total+amt) )*100).toFixed(4);
        $ShareSelector.percentageElement.html('('+p+'%)');
    }
    
    $ShareSelector.returnShare = function() {
        modal = $('#xnumber').parents('.modal');
        modal.bind('hidden',function() {
            $FW.popups.selectShare.callback($ShareSelector.amount);
        });
        modal.modal('hide');
        return false
        
    }
    
    $('#xnumber').keyup(function(evt) {
        var regex = /[^\d]/;
        var string = $('#xnumber').val();
        while ( regex.test(string) ) {
            string = string.replace(/[^\d]/,'');
        }
        if (string!==$('#xnumber').val()) {$('#xnumber').val(string);}
    });
    $('#xnumber').keyup($ShareSelector.updatePercentage);
    $('#shareform').submit($ShareSelector.returnShare);
    $ShareSelector.updatePercentage();
    
    $('#xnumber').parents('.modal').bind('shown', function () {
      $('#xnumber').focus();
    });
    
</script>