<div class="modal-header">
    <h2>Choose a donation method:</h2>
</div>
<div class="modal-body" style="text-align:center">
    <button class="btn primary large" id="paypalBtn">PayPal</button>
</div>
<script type="text/javascript">
    console.log($FW.popups.selectDonationMethod.data);
    $('#paypalBtn').click(function() {
        flux_api_call(
            "pool/create_transaction_key.php?key="+_session["hash"]+"&pool_id=1&flux_to_id="+$FW.popups.selectDonationMethod.data['flux_id'],
            function(json) {
                transaction_key = json;
                window.location = "../pools/paypalPool/donate.php?transaction_key="+transaction_key;
            }
        );
    });
</script>