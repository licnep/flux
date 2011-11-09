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
    <h2>Transactions:</h2>
    <table id="transactions" style="background-color: white">
        <tbody>
        </tbody>
    </table>
</div>
<div class="well">
    <h2>Account balance:</h2>
    <spann id="myMoney"></span>
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
                for (var i=0;i<json.length;i++) {
                    console.log(json[i]);
                    $('<tr><td>'+json[i]['timestamp']+'</td></tr>').appendTo('#transactions tbody');
                }
                if (json.length==0) {
                    $('<tr><td>No transactions to show.</td></tr>').appendTo('#transactions tbody');
                }
            }
        );
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