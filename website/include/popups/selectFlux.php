<script type="text/javascript" src="css/bootstrap/js/bootstrap-tabs.js"></script>
<div class="modal-header">
    <h2>Select a recipient:</h2>
</div>
<ul class="tabs">
    <li class="active">
        <a href="#mine" id="mine">My stuff</a>
    </li>
    <li>
        <a href="#searchByEmail" id="searchByEmail">Email</a>
    </li>
    <li>
        <a href="#searchByName" id="searchByName">Search by name</a>
    </li>
</ul>
<div id="my-tab-content" class="tab-content">
    <div class="tab-pane active" id="Tmine">
        <div class="well">
            <table id="myAccount" style="background-color: white">
                <tbody>
                    <tr><td><div class="userIcon" /><a href="#"></a></td></tr>
                </tbody>
            </table>
            <table id="myFluxes" style="background-color: white">
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="TsearchByName">
        <div>
            <div class="well">
                Search: <input id="searchbarName" type="text" class="search" name="search" value="" size="30"/>
                <div style="margin-top:10px">
                    <table style="background-color: white">
                        <tbody id="resultsName">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane" id="TsearchByEmail">
        <div>
            <div class="well">
                Email: <input id="searchbarEmail" type="text" class="search" name="search" value="" size="30"/>
                <div style="margin-top:10px">
                    <table style="background-color: white">
                        <tbody id="resultsEmail">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $('.tabs').tabs();
    $('.tabs').bind('change', function (e) {
        $('#T'+e.relatedTarget.id).removeClass('active');
        $('#T'+e.target.id).addClass('active');
        $FluxSelector.switchTab(e.target.id);
    })
    
    $FluxSelector = {};
    
    $FluxSelector.switchTab = function(tabId) {
        switch(tabId) {
            case 'mine':
                break;
            case 'searchByName':
                $('#searchbarName').focus();
                break;
            case 'searchByEmail':
                $('#searchbarEmail').focus();
                break;
        }
    };
       
    $FluxSelector.returnFlux = function(flux_id) {
        //we close the modal and pass the selected value.
        //we pass the value only once the modal is completely closed, to avoid problems if the software must open another modal right after
        modal = $('#myAccount').parents('.modal');
        modal.bind('hidden',function() {
            $FW.popups.selectAFlux.callback({'flux_id':flux_id});
        });
        modal.modal('hide');
    }
    
    //START MY STUFF:======================================================================
    
    //this function loads the list of fluxes owned by the user, and puts it in the first tab
    $FluxSelector.loadMyFluxes = function() {
        $('#myAccount a').html(_session['username']);
        flux_api_call("get_fluxes_owned_by.php?user_id="+_session["uid"],
            function (json) {
                for (var i=0; i<json.length; i++) {
                    (function (flux) {
                        if (flux['userflux']==0) {
                            $('<tr><td><div class="fluxIcon" /><a href="#" onclick="$FluxSelector.returnFlux('
                                +flux['flux_id']+')">'
                                +flux['name']
                                +'</a></td></tr>').appendTo('#myFluxes tbody');
                        }
                    })(json[i]);
                }
            }
        );
    };
    
    $FluxSelector.loadMyFluxes();
    flux_api_call("get_user_flux_ID.php?user_id="+_session["uid"],
        function (json) {
            $('#myAccount a').click(function () {
                $FluxSelector.returnFlux(parseInt(json['flux_id']));
            });
        }
    );
    
    //END [MY STUFF]======================================================================

    //START [SEARCH BY NAME]==============================================================
    $FluxSelector.searchByName = function() {
        flux_api_call("search_flux.php?name="+$("#searchbarName").val(),
            function (json) {
                /*we empty the result set:*/
                $("#resultsName").html("");
                /*we add all results, one by one*/
                for (var i=0;i<Math.min(json.length,5);i++) {
                    //you cannot directly donate to userflux of level 1, because that would make it impossible
                    //for the receiver to redirect the donations wherever he likes
                    if (json[i]['userflux']!=='1') { 
                        $('<tr><td><div class="'+ (json[i]['userflux']==='2'?'userIcon':'fluxIcon') +'" /><a href="#" onclick="$FluxSelector.returnFlux('
                                +json[i]['flux_id']+')">'
                                +json[i]['name']
                                +'</a></td></tr>').appendTo('#resultsName');
                    }
                }
                if (json.length==0) {$("<tr><td>No results.</td></tr>").appendTo("#resultsName");}
            }
        );
    };
    $("#searchbarName").keyup($FluxSelector.searchByName);
    //END [SEARCH BY NAME]==============================================================
    
    //START [SEARCH BY EMAIL]==============================================================
    $FluxSelector.searchByEmail = function() {
        flux_api_call("search_flux.php?email="+$("#searchbarEmail").val(),
            function (json) {
                console.log(json);
                /*we empty the result set:*/
                $("#resultsEmail").html("");
                /*we add all results, one by one*/
                for (var i=0;i<Math.min(json.length,5);i++) {
                    $('<tr><td><div class="userIcon" /><a href="#" onclick="$FluxSelector.returnFlux('
                            +json[i]['flux_id']+')">'
                            +json[i]['name']+' ('+json[i]['email']+')'
                            +'</a></td></tr>').appendTo('#resultsEmail');
                }
                if (json.length==0) {
                    $('<tr><td><div class="emailIcon" /><a href="#" onclick="$FluxSelector.createEmailOnlyFlux('+"$('#searchbarEmail').val()"+')">'
                            +$('#searchbarEmail').val()
                            +"</a></td></tr>").appendTo('#resultsEmail');
                }
            }
        );
    };
    
    $("#searchbarEmail").keyup($FluxSelector.searchByEmail);
    
    $FluxSelector.createEmailOnlyFlux = function(email) {
        //TODO verify it's a properly formatted email
        flux_api_call("create_temp_user.php?email="+email,
            function() {
                flux_api_call("search_flux.php?email="+email,
                    function(data) {
                        $FluxSelector.returnFlux(data[0]['flux_id']);
                    }
                );
            }
        );
    };
    //END [SEARCH BY EMAIL]==============================================================
    
 </script>
