<?php
/*
 * This is the user's home page, showing a list of his fluxes
 */
$head='';
$body='';

/*
 * 1) List of fluxes =====================================================================
 * All the subsequent code is used to generate and show the list of the fluxes owned by the user.
 * We create a container in html and then populate the list through javascript, making a request to the API
 */

ob_start(); //i call ob_start, so that instead of outputting everything directly, we can buffer the output and pass it to the create_page function
?>
<div class="well">
    <h2>Account balance:</h2>
    <span id="myMoney">Loading...</span>
    <a href="#" id="withdrawBtn">Withdraw</a>
</div>
<div class="well">
    <h2>Transactions:</h2>
    <table id="transactions" style="background-color: white">
        <thead>
            <tr>
                <th>Type:</th><th>Amount:</th><th>Date:</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Loading...</td></tr>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        flux_api_call("get_user_balance.php?pool_id=1&user_id="+_session['uid'],
            function (json) {
                $('#myMoney').html(json);
            }
        );
        flux_api_call("get_user_transactions.php?user_id="+_session['uid'], 
            function (json) {
                console.log(json);
                $('#transactions tbody').html('');
                for (var i=0;i<json.length;i++) {
                    if (json[i]['type']==='1') {//withdrawal
                        $('<tr><td>withdrawal</td><td>'
                            +json[i]['amount_readable']+'</td><td>'
                            +json[i]['timestamp']+'</td></tr>').appendTo('#transactions tbody');
                    } else {//donation
                        id = json[i]['flux_to_id']
                        $('<tr><td>donation to <a href="flux.php?id='+id+'" flux_id="'+id+'">'+json[i]['name']+'</a></td><td>'
                            +json[i]['amount_readable']+'</td><td>'
                            +json[i]['timestamp']+'</td></tr>').appendTo('#transactions tbody');
                    }
                    
                }
                if (json.length==0) {
                    $('<tr><td>No transactions to show.</td></tr>').appendTo('#transactions tbody');
                }
            }
        );
        /*Initialize the withdraw button:*/
        $('#withdrawBtn').click(function() {
            flux_api_call(
                "pool/start_withdrawal_get_key.php?key="+_session['hash'],
                function(json) {
                    transaction_key = json;
                    flux_api_call("get_pool_info.php?",function (data) {
                        window.location = data['address']+"/withdraw.php?transaction_key="+transaction_key;
                    });
                });
        });
    });
</script>
<?php
$body .= ob_get_clean();
/*
 * END 1) list of fluxes ===============================================================
 */

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>