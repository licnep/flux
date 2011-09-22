function search() {
    flux_api_call(updateResults, "search_flux.php?string="+$("#searchbar").val());
}

function updateResults(json) {
    /*we empty the result set:*/
    $("#results").html("");
    /*we add all results, one by one*/
    for (var i=0;i<json.length;i++) {
        newFlux = $('<div class="blueBox draggable">'+json[i]["name"]+"</div>").appendTo("#results").draggable({revert:'invalid',connectToSortable:'.receiversUL',helper:'clone'});
        newFlux.attr("flux_id",json[i]["flux_id"]);
        newFlux.attr("name",json[i]["name"]);
        newFlux.attr("description",json[i]["description"]);
    }
    /*after the results, we put the email thingy*/
    if ($("#searchbar").val()!="") {
        $('<div class="separator">Donate to email:</div>').appendTo($("#results"));
        var emailFlux = $('<div class="blueBox draggable">'+$("#searchbar").val()+"</div>")
            .appendTo("#results").draggable({
                revert:'invalid',
                connectToSortable:'.receiversUL',
                helper:'clone'
            });
        emailFlux.attr("emailFlux","1");
    }
}

$(document).ready(function() {
   $("#searchbar").keyup(search)
                  .focus(function() {
                      //clear the field, but only the first time
                      if ($("#searchbar").val()=="insert keyword or email") {
                          $("#searchbar").val("");
                          search();
                      }
                  });
}  
);

