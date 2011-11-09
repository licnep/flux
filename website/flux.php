<?php
/*
 * This is the page that shows a single flux
 */
$head='';
$body='';

//START LIST OF RECIPIENTS

ob_start();
?>
<h1 id="title">Loading...</h1>
<div class="well">
    <h2>Actions:</h2>
    <button class="btn success" id="donateBtn">Support $$$</button>
    <button class="btn info" id="addBtn">Add to one of my fluxes</button>
</div>
<div class="well">
    <h2>Recipients:</h2>
    <table id="recipients" style="background-color: white">
        <thead>
            <tr>
                <th>Name:</th>
                <th>Share:</th>
            </tr>
        </thead>
        <tbody>
            <tr><td>Loading...</td></tr>
        </tbody>
    </table>
    <div class="btn info" id="addRecipientBtn">+ Add a recipient</div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        flux_api_call("get_flux_info.php?flux_id=<?=$_GET['id']?>",
            function(json) {
                $Flux.flux = json;
                $('#title').html($Flux.flux['name']);
                $('#recipients tbody').html('');
                for (var i=0; i<json.children.length; i++) {
                    $Flux.addRecipientToList(json.children[i]);
                }
                //make the edit buttons clickable:
                $.each($('.editIcon'), function(index,elem) {
                    $(elem).click(
                        (function() {
                            var fluxID = $(elem).attr('flux_id');
                            return function() {
                                $FW.popups.selectShare.show(
                                    function(json) {
                                        flux_api_call('change_flux.php?flux_from_id='+_get['id']+'&flux_to_id='+fluxID+'&new_share='+json,
                                            function(json) {
                                                window.location.reload();
                                            });
                                    }, 
                                    100);
                            }
                        })()
                    );
                });
            }
        );
    });
    
    $Flux = {
        flux: {},
        total: null,
        //this function is used to populate the list of recipient fluxes.
        addRecipientToList: function(recipient) {
            var icon = '';
            if (recipient['userflux']==='1') {
                //it's an unclickable, user account'
                $('<tr><td><div class="homeIcon"/><a href="#">'
                    +recipient['name']+"'s account"
                    +'</a></td><td>'+recipient['share']+'    ('+(recipient['share']*100/$Flux.flux['total']).toFixed(4)+' %)'
                    +'<div class="editIcon"/ flux_id="'+recipient['flux_to_id']+'">'+'</td></tr>').appendTo('#recipients tbody');
            }
            else {
                if (recipient['userflux']==='2') {icon = 'userIcon';}
                else if (recipient['userflux']==='0') {icon = 'fluxIcon';}
                $('<tr><td><div class="' + icon +'"/><a href="flux.php?id='+recipient['flux_to_id']+'">'
                    +recipient['name']
                    +'</a></td><td>'+recipient['share']+'    ('+(recipient['share']*100/$Flux.flux['total']).toFixed(4)+' %)'
                    +'<div class="editIcon"/ flux_id="'+recipient['flux_to_id']+'">'+'</td></tr>').appendTo('#recipients tbody');
            }
        }
    }
    
</script>
<?php
$body .= ob_get_clean();

//END LIST OF RECIPIENTS

//START 'ADD RECIPIENT' STUFF
//add the standard flux website functions, to easily ask the user to select a flux, or the amount (share)
ob_start();
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#addRecipientBtn').click(function() {
            //we first ask the user to select a flux
            $FW.popups.selectAFlux.show(function(json) {
                //yay! he has selected the recipient flux.
                //now we show the popup to ask him for the amount
                var selectedFlux = json;
                $FW.popups.selectShare.show(function(json) {
                    console.log(selectedFlux);
                    console.log(json);
                    flux_api_call('change_flux.php?flux_from_id='+_get['id']+'&flux_to_id='+selectedFlux.flux_id+'&new_share='+json,
                        function(json) {
                            window.location.reload();
                        }
                    );
                },100); 
            });
        });
        $('#donateBtn').click(function() {
            $FW.popups.selectDonationMethod.show({flux_id:_get['id']});
        });
        $('#addBtn').click(function() {
            $FW.popups.selectOneOfMyFluxes.show(function(json) {
                var selectedFlux = json;
                $FW.popups.selectShare.show(function(json) {
                    flux_api_call('change_flux.php?flux_from_id='+selectedFlux.flux_id+'&flux_to_id='+_get['id']+'&new_share='+json,
                        function(json) {
                            $FW.popup('<div class="modal-body" style="text-align:center"><h1>Operation successful!</h1><pre>'+json+'</pre></div>');
                        }
                    );
                },100);
            });
        });
    });
</script>
<?php
$head .= ob_get_clean();
//END 'ADD RECIPIENT' STUFF

require_once(dirname(__FILE__).'/scripts/page_creator.php');
$html = create_page($body,$head);
echo $html;
?>