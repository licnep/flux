function FluxContainer(container,flux_id) {
	var thisObj=this; //i think this is necessary to pass the object for the callback, if we use "this", "this" then no longer represents this object
	//set veriables that could be useful later
	this.container = container;
	this.flux_id = flux_id;
	//create the list containing the receivers:
	this.list = $("<ul class=\"receiversUL\"></ul>").sortable({
		helper:'clone',
		connectWith:'.receiversUL',
		stop: function(event,ui) {thisObj.droppedFlux($(ui.item[0]));}
	}).appendTo(container);
	$('<div class="dropHint">(+) Drop fluxes here to add them.</div>').appendTo(container);
	//load the receivers:
	flux_api_call("get_flux_info.php?flux_id="+flux_id,function(array) {thisObj.gotFluxInfo(array);});
}

/*the callback called when we get the list of receivers*/
FluxContainer.prototype.gotFluxInfo = function(array) {
	var thisObj = this;
	for (var i=0;i<array.length;i++) {
		//this.addReceiver(array[i]["flux_to_id"],array[i]["name"],array[i]["description"],array[i]["share"],thisObj);
		newItem = new Receiver(array[i]["flux_to_id"],array[i]["name"],array[i]["description"],array[i]["share"],thisObj);
		newItem.appendTo(this.list);
	}
}

FluxContainer.prototype.changeSubflux = function(subflux_id,new_share) {
	flux_api_call("change_flux.php?flux_from_id="+this.flux_id+"&flux_to_id="+subflux_id+"&new_share="+new_share,function() {});
}

FluxContainer.prototype.droppedFlux = function(droppedObj) {
        /*
         * if it's an emailFlux, it means it's just an email, not a flux yet, so when it's dropped
         * we have to automatically create the associated flux
         */
        if(droppedObj.attr("emailFlux")=="1") {
            return;
        }
	subflux_id = droppedObj.attr('flux_id');
	var thisObj = this;
	flux_api_call("change_flux.php?flux_from_id="+this.flux_id+"&flux_to_id="+subflux_id+"&new_share=10",
            function() {
            newRec = new Receiver(subflux_id,droppedObj.attr("name"),droppedObj.attr("description"),10,thisObj);
            droppedObj.replaceWith(newRec);}
        );
}

FluxContainer.prototype.xButtonCB = function(xbutton) {
	listItem  = $(xbutton).closest(".draggable");
	subflux_id = listItem.attr("flux_id");
	listItem.fadeOut(500); //attention, it only becomes invisible, it's not really eliminated
	flux_api_call("remove_receiver.php?flux_from_id="+this.flux_id+"&flux_to_id="+subflux_id,function() {});
}

function Receiver(id, name, description, share, parent) {
	var obj = parent;
	listItem = $("<li class=\"receiverLI draggable\"></li>");
	listItem.attr("flux_id",id);
	listItem.attr("name",name);
	listItem.attr("description",description);
	inner = name + " - <small>" + description + "</small>";
	recBox = $("<div class=\"receiverBox\">"+inner+"</div>").appendTo(listItem);
	xbutton = $('<div class="xbutton">X</div>').appendTo(recBox);
	xbutton.click(function(event) {
		obj.xButtonCB(event.target);
	});
	var receiverShare = $("<div class=\"receiverShare\">"+share+"</div>").appendTo(recBox);
	$("<div></div>").slider(
		{ value: share, 
		  slide: function(event, ui) {receiverShare.html(ui.value);},
		  change: function(event, ui) {obj.changeSubflux(id,ui.value);} 
		}).appendTo($("<div class=\"sliderContainer\"></div>").appendTo(recBox));
	return listItem;
}
