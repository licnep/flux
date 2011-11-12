<div class="modal-header">
    <h2>Select one of your fluxes:</h2>
</div>
<div class="well">
    <table id="myFluxes" style="background-color: white">
        <tbody>
        </tbody>
    </table>
</div>
<script type="text/javascript">
    $FluxSelector = {};
    $FluxSelector.returnFlux = function(flux_id) {
        //we close the modal and pass the selected value.
        //we pass the value only once the modal is completely closed, to avoid problems if the software must open another modal right after
        modal = $('#myFluxes').parents('.modal');
        modal.bind('hidden',function() {
            $FW.popups.selectOneOfMyFluxes.callback({'flux_id':flux_id});
        });
        modal.modal('hide');
    }
    
    //START MY STUFF:======================================================================
    
    //this function loads the list of fluxes owned by the user, and puts it in the first tab
    $FluxSelector.loadMyFluxes = function() {
        flux_api_call("get_fluxes_owned_by.php?user_id="+_session["uid"],
            function (json) {
                for (var i=0; i<json.length; i++) {
                    (function (flux) {
                        $('<tr><td><div class="'+(flux['userflux']==0?'fluxIcon':'userIcon')+'" /><a href="#" onclick="$FluxSelector.returnFlux('
                            +flux['flux_id']+')">'
                            +flux['name']
                            +'</a></td></tr>').appendTo('#myFluxes tbody');
                    })(json[i]);
                }
            }
        );
    };
    
    $FluxSelector.loadMyFluxes();
    
    //END [MY STUFF]======================================================================
 </script>
